<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        if (isset($_SESSION['user'])) {
            header('location: /');
            exit;
        }

        $builder = new \Gregwar\Captcha\CaptchaBuilder();
        $captcha = $builder->build();

        $_SESSION['phrase'] = $captcha->getPhrase();
        // echo $_SESSION['phrase'];
        return view('index', compact ('builder'));
    }
}
