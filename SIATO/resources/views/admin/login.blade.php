@extends('layouts.admin')

@section('styles')
@endsection

@section('scripts')
@endsection

@section('body')
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('admin.login') }}"><b>Admin</b>Panel</a>
        </div>

        <div class="login-box-body">
            @if(session('login-failed'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Gagal Masuk</h4>
                    {{ session('login-failed') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <div class="form-group has-feedback">
                    <input name="username" type="text" class="form-control" placeholder="Nama pengguna" value="{{ old('username') }}" required autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <input name="password" type="password" class="form-control" placeholder="Kata sandi" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
@endsection
