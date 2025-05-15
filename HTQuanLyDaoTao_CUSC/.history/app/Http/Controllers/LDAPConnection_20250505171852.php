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

        // Tạm thời bỏ qua xác thực LDAP
        $username = $request->input('username');
        $password = $request->input('password');

        // Kiểm tra tài khoản test
        if ($username === 'admin' && $password === 'admin123') {
            // Lưu thông tin đăng nhập vào session
            session(['user' => $username, 'displayname' => 'Administrator']);
            return redirect()->route('home')->with('message', 'Đăng nhập thành công');
        }

        return Redirect::to('error_alert')->with(['error' => 'Bạn đã nhập sai mật khẩu hoặc tài khoản', 'redirectTo' => route('login')]);
    }

    // Phương thức đăng xuất
    public function logout()
    {
        session()->forget('user');
        session()->forget('displayname');
        session()->flush();
        return redirect()->route('home')->with('message', 'Đăng xuất thành công');
    }
}
