<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Redirect;

class LDAPConnection extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required'
        ]);

        // Kiểm tra captcha
        if ($request->input('captcha') !== session('captcha_phrase')) {
            return back()->withErrors(['captcha' => 'Captcha không đúng.'])->withInput();
        }

        // LDAP configuration
        $domain = 'cusc.ctu.vn';
        $username = $request->input('username');
        $password = $request->input('password');
        $ldapconfig = [
            'host' => '10.0.0.2',
            'port' => 389,
            'basedn' => 'dc=cusc,dc=ctu,dc=vn',
        ];

        try {
            $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
            if (!$ds) {
                throw new Exception('Could not connect to LDAP server.');
            }

            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

            $bind = ldap_bind($ds, $username . '@' . $domain, $password);
            if (!$bind) {
                throw new Exception('Bind failed: ' . ldap_error($ds));
            }

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

            ldap_close($ds);

            // Lưu thông tin đăng nhập vào session
            session(['user' => $username, 'displayname' => $displayName]);

            return redirect()->route('home')->with('message', 'Login correct');
        } catch (Exception $e) {
            if (isset($ds)) {
                ldap_close($ds);
            }
            return Redirect::to('error_alert')->with(['error' => 'Bạn đã nhập sai mật khẩu hoặc tài khoản', 'redirectTo' => route('login')]);
        }
    }

    // Phương thức đăng xuất
    public function logout()
    {
        session()->forget('user');
        session()->forget('displayname');
        return redirect()->route('home')->with('message', 'Đăng xuất thành công');
    }
}
