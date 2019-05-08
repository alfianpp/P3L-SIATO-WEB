<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
            }

            body {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-align: center;
                align-items: center;
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }

            .form-signin {
                width: 100%;
                max-width: 330px;
                padding: 15px;
                margin: auto;
            }
            .form-signin .checkbox {
                font-weight: 400;
            }
            .form-signin .form-control {
                position: relative;
                box-sizing: border-box;
                height: auto;
                padding: 10px;
                font-size: 16px;
            }
            .form-signin .form-control:focus {
                z-index: 2;
            }
            .form-signin input[name="nomor_polisi"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
            .form-signin input[name="nomor_telepon"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        </style>
    </head>

    <body>
        @if($data)

        @else
        <form class="form-signin text-center">
            <h1 class="h3 mb-3 font-weight-normal">Cek Riwayat</h1>
            @if($error)
            <div class="alert alert-danger alert-dismissible text-left fade show" role="alert">
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <input type="text" name="nomor_polisi" class="form-control" placeholder="Nomor Polisi" required autofocus>
            <input type="text" name="nomor_telepon" class="form-control" placeholder="Nomor Telepon" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Cek</button>
            <p class="mt-5 mb-3 text-muted">Copyright &copy; 2019 {{ config('app.name', 'Laravel') }}</p>
        </form>
        @endif
    </body>
</html>
