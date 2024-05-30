<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Redirect;


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

    public function schedules()
{
    if (session()->has('user')) {
        return view('schedules');
    } else {
        return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
    }
}
}
