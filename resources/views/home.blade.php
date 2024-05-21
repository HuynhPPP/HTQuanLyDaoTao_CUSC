<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Login</h1>
    <form action='/ldap' method="POST">
        @csrf
        <input type="text" name="username"> <br>
        <input type="password" name="password"> <br>
        {!! captcha_img() !!}
        <input type="text" name="captcha">
        <input type="submit" value="Login">
    </form>

    @if ($errors->any())
        <div style="color: red;">
            <h1>
                Sai captcha
            </h1>
        </div>
    @endif
</body>
</html>

