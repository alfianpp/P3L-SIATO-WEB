<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h4 class="text-center"><b>SURAT PERINTAH KERJA</b></h4>
                    <hr>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-3 text-right">
                    24-01-19 10:55
                </div>
            </div>

            <div class="row justify-content-start">
                <div class="col-md-12">
                    <h5>SS-240119-141</h5>
                </div>
            </div>

            <div class="row justify-content-start">
                <div class="col-md-1">
                    Cust <br>
                    Telepon <br>
                    Motor <br>
                </div>

                <div class="col-md-8">
                    Stefanus Rojali <br>
                    081801234567 <br>
                    Yamaha Jupiter Z AB1234BA <br>
                </div>

                <div class="col-md-1">
                    CS <br>
                    Montir <br>
                </div>

                <div class="col-md-2">
                    Natalia Ramona <br>
                    Toni Sutarko <br>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h6 class="text-center"><b>SPAREPARTS</b></h6>
                    <hr>
                </div>
            </div>

            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Merk</th>
                        <th>Rak</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1111-5TZ-905</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td>2222-5TZ-987</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"></td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h6 class="text-center"><b>SERVICE</b></h6>
                    <hr>
                </div>
            </div>

            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Service Mesin</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td>Service Kabel</td>
                        <td class="text-right"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
