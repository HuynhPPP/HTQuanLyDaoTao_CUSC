<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Notification</title>
</head>
<body>
    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
            window.history.back();
        </script>
    @endif
</body>
</html>
</html>