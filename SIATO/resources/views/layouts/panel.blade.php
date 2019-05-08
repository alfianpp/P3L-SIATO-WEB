@extends('layouts.admin')

@section('body')
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <span class="logo-mini"><b>A</b>P</span>
                <span class="logo-lg"><b>Admin</b>Panel</span>
            </a>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span><i class="fa fa-user fa-fw"></i> {{ Auth::user()->nama }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENU UTAMA</li>
                    <li class="{{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>
                    
                    <li class="header">PENGELOLAAN DATA</li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.pegawai' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.pegawai') }}"><i class="fa fa-database"></i> <span>Pegawai</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.spareparts' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.spareparts') }}"><i class="fa fa-database"></i> <span>Spareparts</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.supplier' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.supplier') }}"><i class="fa fa-database"></i> <span>Supplier</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.jasaservice' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.jasaservice') }}"><i class="fa fa-database"></i> <span>Jasa Service</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.konsumen' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.konsumen') }}"><i class="fa fa-database"></i> <span>Konsumen</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.kendaraan' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.kendaraan') }}"><i class="fa fa-database"></i> <span>Kendaraan</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.kelola.cabang' ? 'active' : '' }}">
                        <a href="{{ route('admin.kelola.cabang') }}"><i class="fa fa-database"></i> <span>Cabang</span></a>
                    </li>

                    <li class="header">TRANSAKSI</li>
                    <li class="{{ Route::currentRouteName() == 'admin.transaksi.pengadaan_barang' ? 'active' : '' }}">
                        <a href="{{ route('admin.transaksi.pengadaan_barang') }}"><i class="fa fa-database"></i> <span>Pengadaan Barang</span></a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'admin.transaksi.penjualan' ? 'active' : '' }}">
                        <a href="{{ route('admin.transaksi.penjualan') }}"><i class="fa fa-database"></i> <span>Penjualan</span></a>
                    </li>
                </ul>
            </section>
        </aside>

        @yield('content')

        <footer class="main-footer">
            Copyright &copy; 2019 {{ config('app.name') }}
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('admin-lte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-lte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-lte/dist/js/adminlte.min.js') }}"></script>

    @yield('scripts')
    
    @yield('page-script')
</body>
@endsection