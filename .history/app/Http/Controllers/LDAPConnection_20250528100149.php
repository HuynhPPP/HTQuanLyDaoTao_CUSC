<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class LDAPConnection extends Controller
{
    public function index(Request $request)
    {
        // Log request data
        Log::info('LDAP Login Attempt', [
            'username' => $request->input('username'),
            'has_password' => !empty($request->input('password')),
            'has_captcha' => !empty($request->input('captcha'))
        ]);

        // Validate input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required'
        ]);

        // Kiểm tra captcha
        // if ($request->input('captcha') !== session('captcha_phrase')) {
        //     return back()->withErrors(['captcha' => 'Captcha không đúng.'])->withInput();
        // }

        // LDAP configuration
        $domain = 'CUSC';  // Changed from cusc.ctu.vn to CUSC
        $username = $request->input('username');
        $password = $request->input('password');
        $ldapconfig = [
            'host' => '10.0.0.2',
            'port' => 389,
            'basedn' => 'dc=cusc,dc=ctu,dc=vn',
        ];

        try {
            Log::info('Attempting LDAP connection', ['host' => $ldapconfig['host'], 'port' => $ldapconfig['port']]);

            $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
            if (!$ds) {
                throw new Exception('Could not connect to LDAP server.');
            }

            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);

            $bind_string = $domain . '\\' . $username;
            Log::info('Attempting LDAP bind', ['bind_string' => $bind_string]);

            $bind = ldap_bind($ds, $bind_string, $password);

            if (!$bind) {
                $error = ldap_error($ds);
                $errno = ldap_errno($ds);
                Log::error('LDAP bind failed', ['error' => $error, 'errno' => $errno]);
                throw new Exception("Bind failed: $error (Errno: $errno)");
            }

            Log::info('LDAP bind successful, searching for user');

            $isITuser = ldap_search($ds, $ldapconfig['basedn'], '(&(objectClass=User)(sAMAccountName=' . $username . '))');
            if (!$isITuser) {
                throw new Exception('Login incorrect');
            }

            $entries = ldap_get_entries($ds, $isITuser);
            if ($entries['count'] == 0) {
                throw new Exception('User not found.');
            }

            // Truy xuất displayName từ kết quả tìm kiếm
            $displayName = $entries[0]['displayname'][0] ?? $username;
            Log::info('User found', ['username' => $username, 'displayname' => $displayName]);

            // Lấy group (vai trò) từ LDAP
            $role = 'student'; // Mặc định là sinh viên
            if (isset($entries[0]['memberof'])) {
                $groups = $entries[0]['memberof'];
                foreach ($groups as $key => $group) {
                    if ($key === 'count') continue;
                    if (strpos($group, 'CN=Admin') !== false) {
                        $role = 'admin';
                        break;
                    }
                    if (strpos($group, 'CN=Staff') !== false) {
                        $role = 'staff';
                        break;
                    }
                    if (strpos($group, 'CN=Teacher') !== false) {
                        $role = 'teacher';
                        break;
                    }
                    if (strpos($group, 'CN=Student') !== false) {
                        $role = 'student';
                        break;
                    }
                }
            }

            ldap_close($ds);

            // Lưu thông tin đăng nhập vào session
            session(['user' => $username, 'displayname' => $displayName, 'role' => $role]);

            return redirect()->route('about')->with('success', 'Đăng nhập thành công!');
        } catch (Exception $e) {
            if (isset($ds) && is_resource($ds)) {
                ldap_close($ds);
            }
            
            // Ghi log lỗi chi tiết
            Log::error('LDAP Error', [
                'message' => $e->getMessage(),
                'username' => $username,
                'trace' => $e->getTraceAsString()
            ]);

            $errorMsg = $e->getMessage();
            if (str_contains($errorMsg, 'Invalid credentials')) {
                $userMsg = 'Tên đăng nhập hoặc mật khẩu không đúng. Vui lòng kiểm tra lại!';
            } elseif (str_contains($errorMsg, 'Could not connect')) {
                $userMsg = 'Không thể kết nối đến máy chủ xác thực. Vui lòng thử lại sau!';
            } else {
                $userMsg = 'Đã xảy ra lỗi kết nối đến máy chủ xác thực. Vui lòng thử lại sau!';
            }

            return redirect()->route('login')->with('error', $userMsg);
        }
    }

    // Phương thức đăng xuất
    public function logout()
    {
        session()->forget('user');
        session()->forget('displayname');
        session()->forget('role');
        session()->flush();
        return redirect()->route('about')->with('success', 'Đăng xuất thành công!');
    }

    public function getAllADAccounts()
    {
        try {
            $ds = ldap_connect(env('LDAP_HOST'), env('LDAP_PORT'));
            if (!$ds) {
                throw new Exception('Không thể kết nối đến máy chủ LDAP.');
            }

            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, env('LDAP_TIMEOUT', 10));
            
            // Bind với tài khoản admin từ .env
            $bind = ldap_bind($ds, env('LDAP_USERNAME'), env('LDAP_PASSWORD'));
            if (!$bind) {
                throw new Exception('Không thể xác thực với máy chủ LDAP.');
            }

            // Tìm kiếm tất cả user
            $search = ldap_search($ds, env('LDAP_BASE_DN'), '(&(objectClass=user)(objectCategory=person))');
            if (!$search) {
                throw new Exception('Lỗi tìm kiếm tài khoản.');
            }

            $entries = ldap_get_entries($ds, $search);
            $accounts = [];

            // Bỏ qua index 'count'
            for ($i = 0; $i < $entries['count']; $i++) {
                $entry = $entries[$i];
                
                // Kiểm tra các thuộc tính tồn tại
                $account = [
                    'username' => $entry['samaccountname'][0] ?? '',
                    'display_name' => $entry['displayname'][0] ?? '',
                    'email' => $entry['mail'][0] ?? '',
                    'enabled' => ($entry['useraccountcontrol'][0] & 2) ? false : true
                ];

                // Xác định user_type từ memberOf
                $account['user_type'] = 'student'; // Mặc định là student
                if (isset($entry['memberof'])) {
                    foreach ($entry['memberof'] as $group) {
                        if (strpos($group, 'CN=Admin') !== false) {
                            $account['user_type'] = 'admin';
                            break;
                        } elseif (strpos($group, 'CN=Staff') !== false) {
                            $account['user_type'] = 'staff';
                            break;
                        } elseif (strpos($group, 'CN=Teacher') !== false) {
                            $account['user_type'] = 'teacher';
                            break;
                        }
                    }
                }

                $accounts[] = $account;
            }

            ldap_close($ds);
            return $accounts;

        } catch (Exception $e) {
            if (isset($ds) && is_resource($ds)) {
                ldap_close($ds);
            }
            throw $e;
        }
    }
}
