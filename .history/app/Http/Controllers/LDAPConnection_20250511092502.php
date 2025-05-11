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

            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
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
                $userMsg = 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại sau!';
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
}
