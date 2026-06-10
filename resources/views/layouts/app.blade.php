<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <title>@yield('title', config('app.name', 'HAMORA'))</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .page-wrapper {
            min-height: 100vh;
        }

        .sidebar {
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--glass-border);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--emerald-light);
        }

        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 12px 18px;
            font-family: inherit;
            font-size: 15px;
            transition: background-color var(--transition-fast), border-color var(--transition-fast), box-shadow var(--transition-fast);
            -webkit-tap-highlight-color: transparent;
            color-scheme: light;
        }

        .form-control:focus,
        .form-select:focus,
        .form-control:focus-visible,
        .form-select:focus-visible {
            outline: none !important;
            border-color: var(--emerald-light);
            box-shadow: 0 0 20px rgba(52, 211, 153, 0.2);
            background-color: rgba(255, 255, 255, 0.98);
            color: var(--text-primary);
        }

        .form-control:active,
        .form-select:active {
            transition: none !important;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-select option {
            background: var(--bg-dark);
            color: var(--text-primary);
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .btn-emerald {
            background: linear-gradient(135deg, var(--emerald), var(--emerald-light));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-fast);
            box-shadow: 0 8px 24px rgba(5, 150, 105, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-emerald:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(5, 150, 105, 0.4);
            color: white;
        }

        .btn-outline-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 12px 28px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline-glass:hover {
            background: var(--glass-hover);
            border-color: var(--emerald-light);
            color: var(--text-primary);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 8px;
        }

        .card-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .filter-row .form-group {
            flex: 1;
            min-width: 150px;
            margin-bottom: 0;
        }

        .filter-row .form-group label {
            font-size: 12px;
            margin-bottom: 4px;
            display: block;
        }

        .filter-row .form-group .form-control,
        .filter-row .form-group .form-select {
            padding: 8px 14px;
            font-size: 13px;
        }

        .filter-actions {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            flex: 1;
            min-width: 150px;
        }

        .filter-btn-row {
            display: flex;
            gap: 8px;
        }

        .badge-aktif {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
        }

        .badge-draft {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
        }

        .badge-direvisi {
            background: rgba(59, 130, 246, 0.15);
            color: var(--info);
        }

        .badge-kadaluarsa {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
        }

        .badge-aktif::before,
        .badge-draft::before,
        .badge-direvisi::before,
        .badge-kadaluarsa::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .badge-aktif::before {
            background: var(--success);
            box-shadow: 0 0 8px var(--success);
        }

        .badge-draft::before {
            background: var(--warning);
            box-shadow: 0 0 8px var(--warning);
        }

        .badge-direvisi::before {
            background: var(--info);
            box-shadow: 0 0 8px var(--info);
        }

        .badge-kadaluarsa::before {
            background: var(--danger);
            box-shadow: 0 0 8px var(--danger);
        }

        .dataTable td,
        .dataTable th {
            vertical-align: middle;
        }

        div.dataTables_wrapper div.dataTables_length select {
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 8px;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 8px;
        }

        div.dataTables_wrapper .dataTables_info {
            color: var(--text-secondary);
        }

        div.dataTables_wrapper .dataTables_paginate .paginate_button {
            color: var(--text-secondary) !important;
            border-radius: 8px !important;
        }

        div.dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--emerald) !important;
            border-color: var(--emerald) !important;
            color: white !important;
        }

        div.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--glass-hover) !important;
            border-color: var(--glass-border) !important;
        }

        .detail-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 16px;
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 16px;
        }

        .pdf-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .pdf-toolbar .btn {
            padding: 6px 14px;
            font-size: 13px;
        }

        .pdf-toolbar span {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .pdf-container {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 20px;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pdf-container canvas {
            max-width: 100%;
            height: auto !important;
        }

        div.dataTables_wrapper div.dataTables_length select,
        div.dataTables_wrapper div.dataTables_filter input {
            background-color: rgba(255, 255, 255, 0.8);
            color: #1a1a1a;
            border-color: rgba(0, 0, 0, 0.1);
        }

        .action-btns {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
        }

        .action-btns .btn {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        .nav-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 12px;
            transition: all var(--transition-fast);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .nav-profile:hover {
            background: var(--glass-hover);
        }

        .nav-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--emerald), var(--gold));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .nav-profile-info {
            line-height: 1.3;
        }

        .nav-profile-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .nav-profile-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        .nav-profile-dropdown {
            position: relative;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 200px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 8px;
            display: none;
            z-index: 1000;
            box-shadow: 0 25px 50px -12px var(--glass-shadow);
        }

        .nav-dropdown-menu.show {
            display: block;
        }

        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            transition: all var(--transition-fast);
            cursor: pointer;
        }

        .nav-dropdown-item:hover {
            background: var(--glass-hover);
            color: var(--text-primary);
        }

        .nav-dropdown-divider {
            height: 1px;
            background: var(--glass-border);
            margin: 4px 8px;
        }

        .nav-profile-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .nav-profile-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        @media (max-width: 992px) {

            .sidebar,
            .sidebar.open {
                background: #f0f0ea !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                background-image: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="background"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="dashboard page-wrapper">
        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header" style="justify-content: center; padding: 12px 16px 16px;">
                <img src="{{ asset('images/logo.webp') }}" alt="HAMORA"
                    style="width: 160px; height: auto; border-radius: 20px; object-fit: contain; filter: drop-shadow(0 8px 32px rgba(5, 150, 105, 0.3));">
            </div>

            <ul class="nav-menu">
                <li class="nav-section">
                    <span class="nav-section-title">Utama</span>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" />
                                    <rect x="14" y="3" width="7" height="7" />
                                    <rect x="3" y="14" width="7" height="7" />
                                    <rect x="14" y="14" width="7" height="7" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">📁 Dokumen</span>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('documents.index') }}"
                                class="nav-link {{ (request()->routeIs('documents.index') && !request()->has('status')) || request()->routeIs('documents.trashed') ? 'active' : '' }}">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                </svg>
                                Semua Dokumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('documents.create') }}"
                                class="nav-link {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                Upload Dokumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#kontrolDokumenCollapse" class="nav-link" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ request()->routeIs('documents.status') ? 'true' : 'false' }}">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                                </svg>
                                <span>Status Dokumen</span>
                                <svg class="chevron-icon ms-auto" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </a>
                            <ul class="collapse {{ request()->routeIs('documents.status') ? 'show' : '' }}"
                                id="kontrolDokumenCollapse">
                                <li class="nav-item">
                                    <a href="{{ route('documents.status', 'aktif') }}"
                                        class="nav-link sub-nav-link {{ request()->routeIs('documents.status') && request()->route('status') === 'aktif' ? 'active' : '' }}">
                                        <svg class="sub-nav-icon" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        <span>Aktif</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('documents.status', 'kadaluarsa') }}"
                                        class="nav-link sub-nav-link {{ request()->routeIs('documents.status') && request()->route('status') === 'kadaluarsa' ? 'active' : '' }}">
                                        <svg class="sub-nav-icon" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                        </svg>
                                        <span>Kadaluarsa</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                @canany(['kelola bidang', 'kelola kategori'])
                    @if (!auth()->user()->hasRole('User'))
                        <li class="nav-section">
                            <span class="nav-section-title">⚙️ Pengaturan</span>
                            <ul>
                                @can('kelola bidang')
                                    <li class="nav-item">
                                        <a href="{{ route('bidang.index') }}"
                                            class="nav-link {{ request()->routeIs('bidang.*') ? 'active' : '' }}">
                                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path
                                                    d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                            </svg>
                                            Bidang
                                        </a>
                                    </li>
                                @endcan
                                @can('kelola kategori')
                                    <li class="nav-item">
                                        <a href="{{ route('kategori.index') }}"
                                            class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                            </svg>
                                            Kategori
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                @endcanany

                @canany(['kelola user', 'kelola role', 'lihat log'])
                    <li class="nav-section">
                        <span class="nav-section-title">Manajemen User</span>
                        <ul>
                            @can('kelola user')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                        Users
                                    </a>
                                </li>
                            @endcan
                            @can('kelola role')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            <path
                                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                        </svg>
                                        Role
                                    </a>
                                </li>
                            @endcan
                            @can('lihat log')
                                <li class="nav-item">
                                    <a href="{{ route('logs.index') }}"
                                        class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                        Log Aktivitas
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class="nav-section">
                    <span class="nav-section-title">Account</span>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>


        </aside>

        {{-- Main Content --}}
        <main class="main-content">
            {{-- Navbar --}}
            <nav class="navbar">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <div class="navbar-right">
                    <div class="nav-profile-dropdown">
                        <div class="nav-profile" id="profileDropdownToggle">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar"
                                    style="width: 36px; height: 36px; border-radius: 10px; object-fit: cover;">
                            @else
                                <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="nav-profile-info">
                                <div class="nav-profile-name">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="nav-profile-role">
                                    {{ auth()->user()->roles->pluck('name')->first() ?? 'User' }}</div>
                            </div>
                        </div>
                        <div class="nav-dropdown-menu" id="profileDropdown">
                            <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="width: 18px; height: 18px;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Edit Profil
                            </a>
                            <div class="nav-dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="nav-dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="width: 18px; height: 18px;">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            @yield('content')
        </main>
    </div>

    <button class="mobile-menu-toggle" id="mobile-menu-toggle" title="Toggle Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
    </button>

    <footer class="site-footer">
        <p>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}. Designed by <a
                href="https://templatemo.com" target="_blank" rel="nofollow">TemplateMo</a></p>
    </footer>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- PDF.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var profileToggle = document.getElementById('profileDropdownToggle');
            var profileDropdown = document.getElementById('profileDropdown');
            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (profileDropdown.classList.contains('show') && !profileDropdown.contains(e.target) &&
                        !profileToggle.contains(e.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>
