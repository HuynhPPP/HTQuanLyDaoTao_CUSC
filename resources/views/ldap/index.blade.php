<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LDAP Login Status</title>
</head>
<body>
    @if (isset($message))
        <div style="color: green;">{{ $message }}</div>
    @endif
</body>
</html>

