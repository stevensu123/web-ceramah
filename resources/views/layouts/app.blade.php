<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tag lainnya -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{asset ('assets/dashboard/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset ('assets/dashboard/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset ('assets/dashboard/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset ('assets/dashboard/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset ('assets/dashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{asset ('assets/dashboard/vendor/libs/apex-charts/apex-charts.css')}}" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="{{asset ('assets/dashboard/css/costum.css')}}" />

    <!-- Helpers -->
    <script src="{{asset ('assets/dashboard/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset ('assets/dashboard/js/config.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>


</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-logo demo">


                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">Smile</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item active">
                        <a href="index.html" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Pages</span>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                            <div data-i18n="Authentications">Cerita Saya</div>
                        </a>
                        <ul class="menu-sub">
                            @can('manage_kategori')
                            <li class="menu-item">
                                <a href="{{route('kategori.index')}}" class="menu-link">
                                    <div data-i18n="Basic">Kategori</div>
                                </a>
                            </li>
                            @endcan
                            @can('manage_users')
                            <li class="menu-item">
                                <a href="{{route('users.index')}}" class="menu-link">
                                    <div data-i18n="Basic">Users</div>
                                </a>
                            </li>
                            @endcan('manage_users')
                            @can('manage_cerita')
                            <li class="menu-item">
                                <a href="{{route('cerita.index')}}" class="menu-link">
                                    <div data-i18n="Basic">Cerita</div>
                                </a>
                            </li>
                            @endcan('manage_cerita')

                            <li class="menu-item">
                                <a href="{{route('roles.index')}}" class="menu-link">
                                    <div data-i18n="Basic">Roles</div>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                            <div data-i18n="Authentications">Cerita Saya</div>
                        </a>
                        <ul class="menu-sub">

                            <li class="menu-item">
                                <a href="{{route('users.index')}}" class="menu-link">
                                    <div data-i18n="Basic"> Users</div>
                                </a>
                            </li>


                            <li class="menu-item">
                                <a href="{{route('users.pending-view')}}" class="menu-link">
                                    <div data-i18n="Basic">Pendding Users</div>
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Notifications -->
                            <li class="nav-item lh-1 me-3">
    <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class="bx bx-bell fs-4 lh-0"></i>
        <span class="badge bg-danger" id="notificationBadge">{{ $notificationCount }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        @if($notifications->isEmpty())
            <li>
                <a class="dropdown-item text-center" href="#">
                    <span class="fw-semibold d-block">Tidak ada notifikasi untuk Anda</span>
                </a>
            </li>
        @else
            @foreach($notifications as $notification)
                <li data-id="{{ $notification->id }}">
                    <a class="dropdown-item" href="#" data-id="{{ $notification->id }}">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <i class="bx bx-info-circle me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ $notification->data['message'] }}</span>
                                <small class="text-muted" data-time="{{ $notification->created_at }}">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach

            <li>
                <div class="dropdown-divider"></div>
            </li>
        @endif
    </ul>
</li>



                            <!-- /Notifications -->

                            <ul id="notifications-list" class="dropdown-menu">
                                <!-- Notifikasi akan dimuat di sini -->
                            </ul>
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{asset ('assets/dashboard/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{asset ('assets/dashboard/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">John Doe</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                                <span class="flex-grow-1 align-middle">Billing</span>
                                                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" style="cursor: pointer" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>


                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            @yield('content')
                        </div>
                        <div class="row">
                        </div>
                    </div>
                    <!-- / Content -->
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                            </div>
                            <div>
                                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                                <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>

                                <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank" class="footer-link me-4">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('assets/dashboard/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('assets/dashboard/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/dashboard/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/dashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('assets/dashboard/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('assets/dashboard/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('assets/dashboard/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('assets/dashboard/js/dashboards-analytics.js')}}"></script>


    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <!-- <script>
        // Inisialisasi badge dengan nilai dari variabel Blade
        $(document).ready(function() {
            var initialCount = parseInt('{{ $notificationCount }}', 10) || 0;
            $('#notificationBadge').text(initialCount);
            console.log('Initial Badge Count:', initialCount);
        });

        // Inisialisasi Pusher dengan key dan cluster yang benar
        var pusher = new Pusher('a01db3fdb4e4ae7a7ada', {
            cluster: 'ap1',
            encrypted: true
        });

        // Berlangganan pada channel
        var channel = pusher.subscribe('admin-channel');

        // Listen event ketika user baru mendaftar
        channel.bind('App\\Events\\UserRegistered', function(data) {
            console.log('Received data:', data); // Debugging: Cek data yang diterima

            // Tambahkan notifikasi baru ke dropdown
            var notificationItem = `
            <li>
                <a class="dropdown-item" href="#">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="bx bx-info-circle me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-semibold d-block">` + data.message + `</span>
                            <small class="text-muted">Just now</small>
                        </div>
                    </div>
                </a>
            </li>
        `;
            $('.dropdown-menu').prepend(notificationItem);

            // Update badge count
            var badge = $('#notificationBadge');
            var currentCount = parseInt(badge.text().trim(), 10) || 0; // Ambil nilai saat ini sebagai integer, default 0
            var newCount = currentCount + 1; // Tambah 1 pada count saat ini
            console.log('Current Badge Count:', currentCount); // Debugging: Cek nilai badge saat ini
            badge.text(newCount); // Update badge dengan nilai baru
            console.log('Updated Badge Count:', newCount); // Debugging: Cek nilai badge yang sudah diperbarui
        });
    </script> -->

    <!-- <script>
        $(document).ready(function() {
            var badge = $('#notificationBadge');

            // Fungsi untuk menampilkan notifikasi baru
            function addNotification(data) {
                var notificationItem = `
            <li>
                <a class="dropdown-item" href="#">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="bx bx-info-circle me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-semibold d-block">` + data.message + `</span>
                            <small class="text-muted">Just now</small>
                        </div>
                    </div>
                </a>
            </li>
        `;
                $('.dropdown-menu').prepend(notificationItem);

                // Update badge count
                var currentCount = parseInt(badge.text().trim(), 10) || 0;
                var newCount = currentCount + 1;
                badge.text(newCount).show(); // Tampilkan badge dan perbarui nilai
                console.log('Updated Badge Count:', newCount);
            }

            // Fungsi untuk inisialisasi Pusher dan berlangganan pada event tertentu
            function initializePusher(eventName) {
                var pusher = new Pusher('a01db3fdb4e4ae7a7ada', {
                    cluster: 'ap1',
                    encrypted: true
                });

                var channel = pusher.subscribe('admin-channel');
                channel.bind(eventName, function(data) {
                    console.log('Received data for ' + eventName + ':', data);
                    if (typeof window.refreshUserTable === 'function') {
                        window.refreshUserTable();
                    }
                    addNotification(data);
                });
            }

            // Set initial badge count
            var initialCount = parseInt('{{ $notificationCount }}', 10) || 0;
            if (initialCount > 0) {
                badge.show().text(initialCount);
            } else {
                badge.hide();
            }

            // Inisialisasi Pusher untuk event-event tertentu
            initializePusher('user.registered');
            initializePusher('user.deleted');
            initializePusher('user.status.updated');
        });
    </script> -->
    <script>
        $(document).ready(function() {
            var badge = $('#notificationBadge');

            function addNotification(data) {
                var notificationItem = `
            <li data-id="${data.id}">
                <a class="dropdown-item" href="#" data-id="${data.id}">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="bx bx-info-circle me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-semibold d-block">${data.message}</span>
                            <small class="text-muted">Just now</small>
                        </div>
                    </div>
                </a>
            </li>
        `;
                $('.dropdown-menu').prepend(notificationItem);

                var currentCount = parseInt(badge.text().trim(), 10) || 0;
                var newCount = currentCount + 1;
                badge.text(newCount).show();
                console.log('Updated Badge Count:', newCount);
            }

            function initializePusher(eventName) {
                var pusher = new Pusher('a01db3fdb4e4ae7a7ada', {
                    cluster: 'ap1',
                    encrypted: true
                });

                var channel = pusher.subscribe('admin-channel');
                channel.bind(eventName, function(data) {
                    console.log('Received data for ' + eventName + ':', data);
                    if (typeof window.refreshUserTable === 'function') {
                        window.refreshUserTable();
                    }
                    addNotification(data);
                });
            }

            var initialCount = parseInt('{{ $notificationCount }}', 10) || 0;
            if (initialCount > 0) {
                badge.show().text(initialCount);
            } else {
                badge.hide();
            }

            initializePusher('user.registered');
            initializePusher('user.deleted');
            initializePusher('user.status.updated');

            // Handle click on notification item
            $(document).on('click', '.dropdown-item', function() {
                var notificationId = $(this).data('id');
                if (notificationId) {
                    $.ajax({
                        url: '/notifications/mark-as-read/' + notificationId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Remove the notification item from the dropdown
                                $('li[data-id="' + notificationId + '"]').remove();

                                // Update badge count
                                var currentCount = parseInt(badge.text().trim(), 10) || 0;
                                var newCount = currentCount - 1;
                                badge.text(newCount);
                                if (newCount <= 0) {
                                    badge.hide();
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk mengupdate waktu relatif
            function updateTime() {
                document.querySelectorAll('small.text-muted[data-time]').forEach(function(element) {
                    const time = element.getAttribute('data-time');
                    const createdAt = new Date(time);
                    const now = new Date();
                    const diffInSeconds = Math.floor((now - createdAt) / 1000);

                    let displayTime;

                    if (diffInSeconds < 60) {
                        displayTime = `${diffInSeconds} detik yang lalu`;
                    } else if (diffInSeconds < 3600) {
                        const diffInMinutes = Math.floor(diffInSeconds / 60);
                        displayTime = `${diffInMinutes} menit yang lalu`;
                    } else if (diffInSeconds < 86400) {
                        const diffInHours = Math.floor(diffInSeconds / 3600);
                        displayTime = `${diffInHours} jam yang lalu`;
                    } else {
                        const diffInDays = Math.floor(diffInSeconds / 86400);
                        displayTime = `${diffInDays} hari yang lalu`;
                    }

                    element.textContent = displayTime;
                });
            }

            // Update waktu setiap detik
            setInterval(updateTime, 1000);

            // Update waktu pada saat pertama kali
            updateTime();
        });
    </script>










</body>
@include('sweetalert::alert')
@yield('javascript')

</html>