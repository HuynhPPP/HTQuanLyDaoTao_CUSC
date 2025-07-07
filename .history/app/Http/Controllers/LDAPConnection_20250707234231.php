<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LDAPConnection extends Controller
{
    public function login(Request $request)
    {
        Log::info('Login Attempt', [
            'username' => $request->input('username'),
            'has_password' => !empty($request->input('password')),
            'has_captcha' => !empty($request->input('captcha')),
        ]);

        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required',
        ]);

        // Kiểm tra captcha nếu dùng
        // if ($request->input('captcha') !== session('captcha_phrase')) {
        //     return back()->withErrors(['captcha' => 'Captcha không đúng.'])->withInput();
        // }

        $username = $request->input('username');
        $password = $request->input('password');

        try {
            $dbUser = DB::table('ldap_accounts')->where('username', $username)->first();

            if (!$dbUser) {
                return redirect()->route('login')->with('error', 'Tài khoản không tồn tại.');
            }

            if (!$dbUser->is_active) {
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
            }

            $storedPassword = $dbUser->initial_password;

            $isValid = Str::startsWith($storedPassword, '$2y$')
                ? Hash::check($password, $storedPassword)
                : $storedPassword === $password;

            if (!$isValid) {
                return redirect()->route('login')->with('error', 'Mật khẩu không chính xác.');
            }

            // Đăng nhập thành công
            session([
                'id' => $dbUser->MaTaiKhoan,
                'user' => $username,
                'displayname' => $dbUser->full_name,
                'role' => $dbUser->role,
            ]);

            return redirect()->route('about')->with('success', 'Đăng nhập thành công!');

        } catch (\Exception $e) {
            Log::error('Login error', [
                'username' => $username,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')->with('error', 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại.');
        }
    }

    // public function index(Request $request)
    // {
    //     // Log request data
    //     Log::info('LDAP Login Attempt', [
    //         'username' => $request->input('username'),
    //         'has_password' => !empty($request->input('password')),
    //         'has_captcha' => !empty($request->input('captcha'))
    //     ]);

    //     // Validate input
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required',
    //         'captcha' => 'required'
    //     ]);

    //     // Kiểm tra captcha
    //     // if ($request->input('captcha') !== session('captcha_phrase')) {
    //     //     return back()->withErrors(['captcha' => 'Captcha không đúng.'])->withInput();
    //     // }

    //     // LDAP configuration
    //     $domain = 'CUSC';  // Changed from cusc.ctu.vn to CUSC
    //     $username = $request->input('username');
    //     $password = $request->input('password');
    //     $ldapconfig = [
    //         'host' => '10.0.0.2',
    //         'port' => 389,
    //         'basedn' => 'dc=cusc,dc=ctu,dc=vn',
    //     ];

    //     try {
    //         Log::info('Attempting LDAP connection', ['host' => $ldapconfig['host'], 'port' => $ldapconfig['port']]);

    //         $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
    //         if (!$ds) {
    //             throw new Exception('Could not connect to LDAP server.');
    //         }

    //         ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    //         ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    //         ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);

    //         $bind_string = $domain . '\\' . $username;
    //         Log::info('Attempting LDAP bind', ['bind_string' => $bind_string]);

    //         $bind = ldap_bind($ds, $bind_string, $password);

    //         if (!$bind) {
    //             // Thử tìm trong bảng ldap_accounts
    //             $dbUser = DB::table('ldap_accounts')->where('username', $username)->first();
    //             if ($dbUser && $dbUser->initial_password === $password) {
    //                 session(['user' => $username, 'displayname' => $dbUser->full_name, 'role' => 'student']);
    //                 return redirect()->route('about')->with('success', 'Đăng nhập thành công !');
    //             }
    //             throw new Exception("Bind failed: " . ldap_error($ds));
    //         }

    //         Log::info('LDAP bind successful, searching for user');

    //         $isITuser = ldap_search($ds, $ldapconfig['basedn'], '(&(objectClass=User)(sAMAccountName=' . $username . '))');
    //         if (!$isITuser) {
    //             throw new Exception('Login incorrect');
    //         }

    //         $entries = ldap_get_entries($ds, $isITuser);
    //         if ($entries['count'] == 0) {
    //             throw new Exception('User not found.');
    //         }

    //         // Truy xuất displayName từ kết quả tìm kiếm
    //         $displayName = $entries[0]['displayname'][0] ?? $username;
    //         Log::info('User found', ['username' => $username, 'displayname' => $displayName]);

    //         // Lấy group (vai trò) từ LDAP
    //         $role = 'student'; // Mặc định là sinh viên
    //         if (isset($entries[0]['memberof'])) {
    //             $groups = $entries[0]['memberof'];
    //             foreach ($groups as $key => $group) {
    //                 if ($key === 'count')
    //                     continue;
    //                 if (strpos($group, 'CN=Admin') !== false) {
    //                     $role = 'admin';
    //                     break;
    //                 }
    //                 if (strpos($group, 'CN=Staff') !== false) {
    //                     $role = 'staff';
    //                     break;
    //                 }
    //                 if (strpos($group, 'CN=Teacher') !== false) {
    //                     $role = 'teacher';
    //                     break;
    //                 }
    //                 if (strpos($group, 'CN=Student') !== false) {
    //                     $role = 'student';
    //                     break;
    //                 }
    //             }
    //         }

    //         ldap_close($ds);

    //         // Lưu thông tin đăng nhập vào session
    //         session(['user' => $username, 'displayname' => $displayName, 'role' => $role]);

    //         return redirect()->route('about')->with('success', 'Đăng nhập thành công!');
    //     } catch (Exception $e) {
    //         if (isset($ds) && is_resource($ds)) {
    //             ldap_close($ds);
    //         }

    //         // Ghi log lỗi chi tiết
    //         Log::error('LDAP Error', [
    //             'message' => $e->getMessage(),
    //             'username' => $username,
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         $errorMsg = $e->getMessage();
    //         if (str_contains($errorMsg, 'Invalid credentials')) {
    //             $userMsg = 'Tên đăng nhập hoặc mật khẩu không đúng. Vui lòng kiểm tra lại!';
    //         } elseif (str_contains($errorMsg, 'Could not connect')) {
    //             $userMsg = 'Không thể kết nối đến máy chủ xác thực. Vui lòng thử lại sau!';
    //         } else {
    //             $userMsg = 'Đã xảy ra lỗi kết nối đến máy chủ xác thực. Vui lòng thử lại sau!';
    //         }

    //         return redirect()->route('login')->with('error', $userMsg);
    //     }
    // }

    // public function index(Request $request)
    // {
    //     Log::info('LDAP Login Attempt', [
    //         'username' => $request->input('username'),
    //         'has_password' => !empty($request->input('password')),
    //         'has_captcha' => !empty($request->input('captcha')),
    //     ]);

    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required',
    //         'captcha' => 'required',
    //     ]);

    //     // Kiểm tra captcha
    //     //     // if ($request->input('captcha') !== session('captcha_phrase')) {
    //     //     //     return back()->withErrors(['captcha' => 'Captcha không đúng.'])->withInput();
    //     //     // }

    //     $username = $request->input('username');
    //     $password = $request->input('password');

    //     // Cấu hình LDAP
    //     $ldapConfig = [
    //         'host' => '10.0.0.2',
    //         'port' => 389,
    //         'basedn' => 'dc=cusc,dc=ctu,dc=vn',
    //         'domain' => 'CUSC',
    //     ];

    //     try {
    //         // ⚠️ 1. Thử xác thực qua bảng ldap_accounts trước (sinh viên)
    //         $dbUser = DB::table('ldap_accounts')->where('username', $username)->first();
    //         if ($dbUser) {
    //             if (
    //                 (Str::startsWith($dbUser->initial_password, '$2y$') && Hash::check($password, $dbUser->initial_password))
    //                 || $dbUser->initial_password === $password
    //             ) {
    //                 session([
    //                     'id' => $dbUser->MaTaiKhoan,
    //                     'user' => $username,
    //                     'displayname' => $dbUser->full_name,
    //                     'role' => $dbUser->role,
    //                 ]);
    //                 return redirect()->route('about')->with('success', 'Đăng nhập thành công!');
    //             }
    //         }


    //         // ⚠️ 2. Nếu không có trong DB → thử xác thực trực tiếp qua LDAP (admin/nhân viên)
    //         $bindString = $ldapConfig['domain'] . '\\' . $username;

    //         $ds = ldap_connect($ldapConfig['host'], $ldapConfig['port']);
    //         if (!$ds) {
    //             throw new Exception('Không thể kết nối LDAP.');
    //         }

    //         ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    //         ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    //         ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);

    //         if (!@ldap_bind($ds, $bindString, $password)) {
    //             throw new Exception('Tên đăng nhập hoặc mật khẩu không đúng.');
    //         }

    //         // ✅ Bind thành công → lấy thông tin người dùng từ LDAP
    //         $search = ldap_search($ds, $ldapConfig['basedn'], "(sAMAccountName=$username)");
    //         $entries = ldap_get_entries($ds, $search);

    //         if ($entries['count'] == 0) {
    //             throw new Exception('Không tìm thấy thông tin người dùng.');
    //         }

    //         $entry = $entries[0];
    //         $displayName = $entry['displayname'][0] ?? $username;

    //         // Tìm vai trò từ memberOf
    //         $role = 'user';
    //         if (!empty($entry['memberof'])) {
    //             foreach ($entry['memberof'] as $key => $group) {
    //                 if ($key === 'count')
    //                     continue;
    //                 if (strpos($group, 'CN=Admin') !== false) {
    //                     $role = 'admin';
    //                     break;
    //                 }
    //                 if (strpos($group, 'CN=Staff') !== false) {
    //                     $role = 'staff';
    //                     break;
    //                 }
    //                 if (strpos($group, 'CN=Teacher') !== false) {
    //                     $role = 'teacher';
    //                     break;
    //                 }
    //             }
    //         }

    //         ldap_close($ds);

    //         session([
    //             'user' => $username,
    //             'password' => $password,
    //             'displayname' => $displayName,
    //             'role' => $role,
    //         ]);

    //         return redirect()->route('about')->with('success', 'Đăng nhập thành công!');
    //     } catch (Exception $e) {
    //         Log::error('LDAP Error', [
    //             'username' => $username,
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         $msg = $e->getMessage();
    //         return redirect()->route('login')->with('error', $msg);
    //     }
    // }

    public function logout()
    {
        session()->forget('user');
        session()->forget('displayname');
        session()->forget('role');
        session()->flush();
        return redirect()->route('about')->with('success', 'Đăng xuất thành công!');
    }
}
