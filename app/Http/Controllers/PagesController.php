<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;


class PagesController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function about()
    {
        return view('about');
    }

    public function ministry()
    {
        return view('ministry');
    }

    public function login()
    {
        if (session()->has('user')) {
            return redirect('/');
        }

        $captchaUrl = route('captcha');
        return view('login', compact('captchaUrl'));

    }
}
