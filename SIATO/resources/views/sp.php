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
                    <h4 class="text-center">Surat Pemesanan</h4>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-3">
                    No: <br>
                    Tanggal: 24 Januari 2019
                  </div>
            </div>

            <div class="row justify-content-start">
                <div class="col-md-3" style="border: 3px dashed #c3c3c3">
                  Kepada Yth: <br>
                  PT Adi Satria <br>
                  Jl. Kemenangan No. 123 <br>
                  Jakarta Pusat <br>
                  (021) 12345678 <br>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p>Mohon untuk di sediakan barang-barang berikut :</p>
                </div>
            </div>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Tipe Barang</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
              </table>

            <div class="row justify-content-end">
                <div class="col-md-3">
                    Hormat kami, <br><br><br><br>
                    (Philips Purnomo)
                  </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
