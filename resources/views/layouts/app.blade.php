<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name', 'HAMORA'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

    <!-- App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>:root{--bs-breadcrumb-divider:"/";}</style>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $activeGroup = 'dashboard';
    if (request()->routeIs('documents.*') || request()->routeIs('mou.*')) $activeGroup = 'dokumen';
    if (request()->routeIs('bidang.*') || request()->routeIs('kategori.*')) $activeGroup = 'pengaturan';
    if (request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('logs.*')) $activeGroup = 'user';
@endphp

<body id="body">
    <!-- leftbar-tab-menu -->
    <div class="leftbar-tab-menu">
        <div class="main-icon-menu">
            <a href="{{ route('dashboard') }}" class="logo logo-metrica d-block text-center">
                <span>
                    <img src="{{ asset('images/logo.webp') }}" alt="logo-small" class="logo-sm" style="width: 40px; height: 40px; border-radius: 10px;">
                </span>
            </a>
            <div class="main-icon-menu-body">
                <div class="position-reletive h-100" data-simplebar style="overflow-x: hidden;">
                    <ul class="nav nav-tabs" role="tablist" id="tab-menu">
                        <li class="nav-item {{ $activeGroup === 'dashboard' ? 'menuitem-active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard" data-bs-trigger="hover">
                            <a href="#MetricaDashboard" id="dashboard-tab" class="nav-link {{ $activeGroup === 'dashboard' ? 'active' : '' }}">
                                <i class="ti ti-smart-home menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeGroup === 'dokumen' ? 'menuitem-active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dokumen" data-bs-trigger="hover">
                            <a href="#MetricaDokumen" id="dokumen-tab" class="nav-link {{ $activeGroup === 'dokumen' ? 'active' : '' }}">
                                <i class="ti ti-files menu-icon"></i>
                            </a>
                        </li>
                        @canany(['kelola bidang', 'kelola kategori'])
                        <li class="nav-item {{ $activeGroup === 'pengaturan' ? 'menuitem-active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan" data-bs-trigger="hover">
                            <a href="#MetricaPengaturan" id="pengaturan-tab" class="nav-link {{ $activeGroup === 'pengaturan' ? 'active' : '' }}">
                                <i class="ti ti-settings menu-icon"></i>
                            </a>
                        </li>
                        @endcanany
                        @canany(['kelola user', 'kelola role', 'lihat log'])
                        <li class="nav-item {{ $activeGroup === 'user' ? 'menuitem-active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen User" data-bs-trigger="hover">
                            <a href="#MetricaUser" id="user-tab" class="nav-link {{ $activeGroup === 'user' ? 'active' : '' }}">
                                <i class="ti ti-users menu-icon"></i>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                </div>
            </div>
            <div class="pro-metrica-end">
                <a href="{{ route('profile.edit') }}" class="profile">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="profile-user" class="rounded-circle thumb-sm">
                    @else
                        <div class="rounded-circle thumb-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #556ee5, #4aa0d5); color: white; font-weight: 600; font-size: 14px;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </a>
            </div>
        </div>

        <div class="main-menu-inner">
            <div class="topbar-left">
                <a href="{{ route('dashboard') }}" class="logo">
                    <span>
                        <h3 class="mb-0" style="font-weight: 700; color: #5156be;">HAMORA</h3>
                    </span>
                </a>
            </div>
            <div class="menu-body navbar-vertical tab-content" data-simplebar>
                <!-- Dashboard Menu -->
                <div id="MetricaDashboard" class="main-icon-menu-pane tab-pane {{ $activeGroup === 'dashboard' ? 'active show' : '' }}" role="tabpanel" aria-labelledby="dashboard-tab">
                    <div class="title-box">
                        <h6 class="menu-title">Dashboard</h6>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="ti ti-chart-bar me-1"></i> Analytics</a>
                        </li>
                    </ul>
                </div>

                <!-- Dokumen Menu -->
                <div id="MetricaDokumen" class="main-icon-menu-pane tab-pane {{ $activeGroup === 'dokumen' ? 'active show' : '' }}" role="tabpanel" aria-labelledby="dokumen-tab">
                    <div class="title-box">
                        <h6 class="menu-title">Arsip</h6>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.*') && !request()->routeIs('documents.status*') ? 'active' : '' }}" href="{{ route('documents.index') }}"><i class="ti ti-file-text me-1"></i> Dokumen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mou.*') ? 'active' : '' }}" href="{{ route('mou.index') }}"><i class="ti ti-note me-1"></i> MOU</a>
                        </li>
                    </ul>
                </div>

                <!-- Pengaturan Menu -->
                @canany(['kelola bidang', 'kelola kategori'])
                <div id="MetricaPengaturan" class="main-icon-menu-pane tab-pane {{ $activeGroup === 'pengaturan' ? 'active show' : '' }}" role="tabpanel" aria-labelledby="pengaturan-tab">
                    <div class="title-box">
                        <h6 class="menu-title">Pengaturan</h6>
                    </div>
                    <ul class="nav flex-column">
                        @can('kelola bidang')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bidang.*') ? 'active' : '' }}" href="{{ route('bidang.index') }}"><i class="ti ti-building me-1"></i> Bidang</a>
                        </li>
                        @endcan
                        @can('kelola kategori')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}"><i class="ti ti-tag me-1"></i> Kategori</a>
                        </li>
                        @endcan
                    </ul>
                </div>
                @endcanany

                <!-- Manajemen User Menu -->
                @canany(['kelola user', 'kelola role', 'lihat log'])
                <div id="MetricaUser" class="main-icon-menu-pane tab-pane {{ $activeGroup === 'user' ? 'active show' : '' }}" role="tabpanel" aria-labelledby="user-tab">
                    <div class="title-box">
                        <h6 class="menu-title">Manajemen User</h6>
                    </div>
                    <ul class="nav flex-column">
                        @can('kelola user')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="ti ti-user me-1"></i> Users</a>
                        </li>
                        @endcan
                        @can('kelola role')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}"><i class="ti ti-shield me-1"></i> Role</a>
                        </li>
                        @endcan
                        @can('lihat log')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}" href="{{ route('logs.index') }}"><i class="ti ti-clock me-1"></i> Log Aktivitas</a>
                        </li>
                        @endcan
                    </ul>
                </div>
                @endcanany
            </div>
        </div>
    </div>

    <!-- Top Bar Start -->
    <div class="topbar">
        <nav class="navbar-custom" id="navbar-custom">
            <ul class="list-unstyled topbar-nav float-end mb-0">
                <li class="dropdown">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" style="position: relative;">
                        <i class="ti ti-bell" style="font-size: 20px;"></i>
                        @if($notifikasiCount > 0)
                            <span class="position-absolute badge rounded-pill bg-danger" style="font-size: 10px; padding: 2px 5px; top: 2px; right: -4px; min-width: 18px;">{{ $notifikasiCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 380px; max-height: 420px; overflow-y: auto;">
                        <h6 class="dropdown-header" style="font-size: 13px;">Notifikasi Peringatan</h6>

                        @if($expiringMou->count() > 0)
                            <div class="px-3 py-1 text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                                <i class="ti ti-note me-1"></i> MOU ({{ $expiringMou->count() }})
                            </div>
                            @foreach($expiringMou as $m)
                                <a class="dropdown-item py-2" href="{{ route('mou.show', $m->id) }}">
                                    <div class="fw-medium" style="font-size: 13px;">{{ Str::limit($m->judul, 40) }}</div>
                                    <small class="text-muted" style="font-size: 12px;">{{ $m->nomor }} · s/d {{ $m->akhir_perjanjian->format('d/m/Y') }}</small>
                                    <div class="mt-1"><span class="badge bg-warning text-dark" style="font-size: 10px; padding: 3px 6px;">{{ $m->akhir_perjanjian->diffForHumans() }}</span></div>
                                </a>
                            @endforeach
                        @endif

                        @if($expiringDoc->count() > 0)
                            <div class="px-3 py-1 text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                                <i class="ti ti-file-text me-1"></i> Dokumen ({{ $expiringDoc->count() }})
                            </div>
                            @foreach($expiringDoc as $d)
                                <a class="dropdown-item py-2" href="{{ route('documents.show', $d->id) }}">
                                    <div class="fw-medium" style="font-size: 13px;">{{ Str::limit($d->nama_dokumen, 40) }}</div>
                                    <small class="text-muted" style="font-size: 12px;">{{ $d->nomor_dokumen }} · s/d {{ $d->tanggal_berlaku->format('d/m/Y') }}</small>
                                    <div class="mt-1"><span class="badge bg-warning text-dark" style="font-size: 10px; padding: 3px 6px;">{{ $d->tanggal_berlaku->diffForHumans() }}</span></div>
                                </a>
                            @endforeach
                        @endif

                        @if($expiringMou->count() === 0 && $expiringDoc->count() === 0)
                            <div class="text-center py-4 px-3">
                                <i class="ti ti-check-circle text-success" style="font-size: 28px;"></i>
                                <p class="text-muted mt-2 mb-0" style="font-size: 13px;">Tidak ada peringatan saat ini</p>
                            </div>
                        @endif
                    </div>
                </li>
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="profile-user" class="rounded-circle me-2 thumb-sm" />
                            @else
                                <div class="rounded-circle me-2 thumb-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: linear-gradient(135deg, #556ee5, #4aa0d5); color: white; font-weight: 600; font-size: 12px;">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <small class="d-none d-md-block font-11">{{ auth()->user()->roles->pluck('name')->first() ?? 'User' }}</small>
                                <span class="d-none d-md-block fw-semibold font-12">{{ auth()->user()->name ?? 'User' }} <i class="mdi mdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti ti-user font-16 me-1 align-text-bottom"></i> Profile</a>
                        <div class="dropdown-divider mb-0"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-power font-16 me-1 align-text-bottom"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>

            <ul class="list-unstyled topbar-nav mb-0">
                <li>
                    <button class="nav-link button-menu-mobile nav-icon" id="togglemenu">
                        <i class="ti ti-menu-2"></i>
                    </button>
                </li>
                <li class="hide-phone app-search">
                    <form role="search" action="#" method="get">
                        <input type="search" name="search" class="form-control top-search mb-0" placeholder="Cari...">
                        <button type="submit"><i class="ti ti-search"></i></button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
    <!-- Top Bar End -->

    <div class="page-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Vendor JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;"></div>

    <!-- App JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        (function() {
            function showToast(type, message) {
                var container = document.getElementById('toast-container');
                if (!container || !message) return;

                var icons = {
                    success: 'ti ti-check',
                    error: 'ti ti-x',
                    warning: 'ti ti-alert-triangle',
                    info: 'ti ti-info-circle'
                };
                var colors = {
                    success: '#22c55e',
                    error: '#dc2626',
                    warning: '#eab308',
                    info: '#0ea5e9'
                };

                var toast = document.createElement('div');
                toast.className = 'hamora-toast';
                toast.style.borderLeftColor = colors[type] || colors.info;
                toast.innerHTML =
                    '<div class="hamora-toast-icon" style="background:' + (colors[type] || colors.info) + '20; color:' + (colors[type] || colors.info) + '">' +
                        '<i class="' + (icons[type] || icons.info) + '"></i>' +
                    '</div>' +
                    '<div class="hamora-toast-body">' + message + '</div>' +
                    '<button class="hamora-toast-close" onclick="this.parentElement.remove()">&times;</button>';

                container.appendChild(toast);

                requestAnimationFrame(function() {
                    toast.classList.add('show');
                });

                setTimeout(function() {
                    toast.classList.remove('show');
                    setTimeout(function() { toast.remove(); }, 300);
                }, 5000);
            }

            @if(session('success'))
                showToast('success', {!! json_encode(session('success')) !!});
            @endif
            @if(session('error'))
                showToast('error', {!! json_encode(session('error')) !!});
            @endif
            @if(session('warning'))
                showToast('warning', {!! json_encode(session('warning')) !!});
            @endif

            window.showToast = showToast;
        })();
    </script>

    <!-- Fix DataTables header on sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('togglemenu');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    setTimeout(function() {
                        if (typeof $ !== 'undefined' && $.fn.DataTable) {
                            $.fn.DataTable.tables({ visible: true, api: true }).columns.adjust();
                        }
                    }, 350);
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
