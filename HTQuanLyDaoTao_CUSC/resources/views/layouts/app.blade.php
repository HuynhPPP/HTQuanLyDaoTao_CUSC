<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hệ thống quản lý đào tạo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
        /* Floating Action Buttons */
        .floating-buttons {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
        }

        .floating-buttons-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .floating-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .floating-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }

        .floating-button:active {
            transform: translateY(0);
        }

        .floating-button i {
            color: white;
            font-size: 1.2rem;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .floating-buttons {
                bottom: 10px;
                left: 10px;
            }

            .floating-button {
                width: 40px;
                height: 40px;
            }

            .floating-button i {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>


    @include('layouts.header')


    @yield('content')


    @include('layouts.footer')


</body>
</html>
