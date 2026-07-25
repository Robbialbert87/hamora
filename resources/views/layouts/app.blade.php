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
                        <img src="{{ asset('images/logo.webp') }}" alt="logo-large" class="logo-lg" style="height: 40px;">
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
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Analytics</a>
                        </li>
                    </ul>
                </div>

                <!-- Dokumen Menu -->
                <div id="MetricaDokumen" class="main-icon-menu-pane tab-pane {{ $activeGroup === 'dokumen' ? 'active show' : '' }}" role="tabpanel" aria-labelledby="dokumen-tab">
                    <div class="title-box">
                        <h6 class="menu-title">Dokumen</h6>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->routeIs('documents.index') && !request()->has('status')) || request()->routeIs('documents.trashed') ? 'active' : '' }}" href="{{ route('documents.index') }}">Semua Dokumen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mou*') ? 'active' : '' }}" href="{{ route('mou.index') }}">MOU</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.create') || request()->routeIs('documents.create.baru') || request()->routeIs('documents.create.mou') ? 'active' : '' }}" href="{{ route('documents.create') }}">Upload Dokumen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.create.update*') ? 'active' : '' }}" href="{{ route('documents.create.update') }}">Update Dokumen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('documents.status*') ? 'active' : '' }}" href="#sidebarStatusDokumen" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('documents.status') ? 'true' : 'false' }}">Status Dokumen <i class="ti ti-chevron-right collapse-icon ms-auto"></i></a>
                            <div class="collapse {{ request()->routeIs('documents.status') ? 'show' : '' }}" id="sidebarStatusDokumen">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('documents.status') && request()->route('status') === 'aktif' ? 'active' : '' }}" href="{{ route('documents.status', 'aktif') }}"><span class="submenu-dot"></span>Aktif</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('documents.status') && request()->route('status') === 'kadaluarsa' ? 'active' : '' }}" href="{{ route('documents.status', 'kadaluarsa') }}"><span class="submenu-dot"></span>Kadaluarsa</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('documents.status') && request()->route('status') === 'dicabut' ? 'active' : '' }}" href="{{ route('documents.status', 'dicabut') }}"><span class="submenu-dot"></span>Dicabut</a>
                                    </li>
                                </ul>
                            </div>
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
                            <a class="nav-link {{ request()->routeIs('bidang.*') ? 'active' : '' }}" href="{{ route('bidang.index') }}">Bidang</a>
                        </li>
                        @endcan
                        @can('kelola kategori')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a>
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
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a>
                        </li>
                        @endcan
                        @can('kelola role')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">Role</a>
                        </li>
                        @endcan
                        @can('lihat log')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}" href="{{ route('logs.index') }}">Log Aktivitas</a>
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

    <!-- App JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

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
