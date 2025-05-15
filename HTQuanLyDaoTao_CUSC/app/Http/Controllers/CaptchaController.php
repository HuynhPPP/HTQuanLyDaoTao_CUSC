<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaController extends Controller
{
    public function generateCaptcha()
    {
        $builder = new CaptchaBuilder();
        $builder->build();

        session(['captcha_phrase' => $builder->getPhrase()]);
        return response($builder->output())->header('Content-Type', 'image/jpeg');
    }
}
