<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'PSL Track')
    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;

            background-color: #ffffff;

            border-right: 1px solid #e5e7eb;

            z-index: 1000;
        }

        .sidebar-brand {
            height: 70px;

            display: flex;
            align-items: center;

            padding: 0 20px;

            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-brand h5 {
            margin: 0;

            font-weight: 700;

            color: #1f2937;
        }

        .sidebar-brand small {
            color: #6b7280;
        }

        .sidebar-menu {
            padding: 20px 12px;
        }

        .sidebar-menu-title {
            font-size: 11px;

            font-weight: 600;

            color: #9ca3af;

            text-transform: uppercase;

            margin: 10px 12px;
        }

        .sidebar-link {
            display: flex;

            align-items: center;

            gap: 12px;

            padding: 11px 14px;

            margin-bottom: 4px;

            border-radius: 8px;

            color: #4b5563;

            text-decoration: none;

            font-size: 14px;
        }

        .sidebar-link:hover {
            background-color: #f3f4f6;

            color: #111827;
        }

        .sidebar-link.active {
            background-color: #e8f1ff;

            color: #2563eb;

            font-weight: 600;
        }

        .sidebar-link i {
            font-size: 17px;
        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .main-wrapper {
            margin-left: 250px;

            min-height: 100vh;
        }


        /* =========================
           NAVBAR
        ========================= */

        .top-navbar {
            height: 70px;

            background-color: #ffffff;

            border-bottom: 1px solid #e5e7eb;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;
        }

        .page-title {
            font-size: 20px;

            font-weight: 600;

            color: #1f2937;
        }


        /* =========================
           USER PROFILE
        ========================= */

        .user-profile {
            display: flex;

            align-items: center;

            gap: 10px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;

            border-radius: 50%;

            background-color: #2563eb;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 600;
        }

        .user-info {
            line-height: 1.2;
        }

        .user-name {
            font-size: 14px;

            font-weight: 600;

            color: #1f2937;
        }

        .user-role {
            font-size: 12px;

            color: #6b7280;

            text-transform: capitalize;
        }


        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 30px;
        }


        /* =========================
           FLASH MESSAGE
        ========================= */

        .alert {
            border-radius: 8px;
        }


        /* =========================
           CARD
        ========================= */

        .card {
            border: 1px solid #e5e7eb;

            border-radius: 10px;

            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

    </style>

    @stack('styles')

</head>


<body>

    {{-- =========================
         SIDEBAR
    ========================== --}}

    <aside class="sidebar">

        {{-- Logo / Brand --}}
        <div class="sidebar-brand">

            <div>
                <h5>PSL Track</h5>

                <small>
                    Digital Task Monitoring
                </small>
            </div>

        </div>


        {{-- Menu --}}
        <div class="sidebar-menu">

            <div class="sidebar-menu-title">
                Menu Utama
            </div>


            {{-- Dashboard --}}
            <a
                href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >

                <i class="bi bi-grid"></i>

                <span>
                    Dashboard
                </span>

            </a>


            {{-- KPI --}}
            <a
                href="#"
                class="sidebar-link"
            >

                <i class="bi bi-bar-chart"></i>

                <span>
                    KPI Tracker
                </span>

            </a>


            {{-- Monitoring --}}
            <a
                href="#"
                class="sidebar-link"
            >

                <i class="bi bi-clipboard-check"></i>

                <span>
                    Monitoring
                </span>

            </a>


            {{-- Tasks --}}
            <a
                href="#"
                class="sidebar-link"
            >

                <i class="bi bi-list-task"></i>

                <span>
                    Task Monitoring
                </span>

            </a>


            <div class="sidebar-menu-title mt-4">
                Pengaturan
            </div>


            {{-- User Management --}}
            @if(auth()->check() && auth()->user()->role === 'manager')

                <a
                    href="{{ route('users.index') }}"
                    class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                >

                    <i class="bi bi-people"></i>

                    <span>
                        Manajemen User
                    </span>

                </a>

            @endif


        </div>

    </aside>


    {{-- =========================
         MAIN WRAPPER
    ========================== --}}

    <div class="main-wrapper">


        {{-- =========================
             NAVBAR
        ========================== --}}

        <nav class="top-navbar">

            <div>

                <div class="page-title">
                    @yield('page-title', 'Dashboard')
                </div>

            </div>


            {{-- User --}}
            @auth

                <div class="dropdown">

                    <button
                        class="btn d-flex align-items-center gap-2"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <div class="user-avatar">

                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}

                        </div>

                        <div class="user-info text-start">

                            <div class="user-name">

                                {{ auth()->user()->nama }}

                            </div>

                            <div class="user-role">

                                {{ auth()->user()->role }}

                            </div>

                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </button>


                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>

                            <a
                                class="dropdown-item"
                                href="#"
                            >

                                <i class="bi bi-person me-2"></i>

                                Profil

                            </a>

                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <li>

                            <form
                                action="{{ route('logout') }}"
                                method="POST"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item text-danger"
                                >

                                    <i class="bi bi-box-arrow-right me-2"></i>

                                    Logout

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            @endauth

        </nav>


        {{-- =========================
             CONTENT
        ========================== --}}

        <main class="content">


            {{-- Flash Success --}}
            @if(session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            {{-- Flash Error --}}
            @if(session('error'))

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >

                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            {{-- Validation Error --}}
            @if($errors->any())

                <div
                    class="alert alert-danger"
                    role="alert"
                >

                    <strong>
                        Terdapat kesalahan:
                    </strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Halaman memasukkan content di sini --}}
            @yield('content')


        </main>

    </div>


    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>


    @stack('scripts')

</body>

</html>