<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Trung tâm Tiếng Trung') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Feather Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Thêm style cho component -->
    <style>
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }

    .pagination li {
        margin: 0 2px;
    }

    .pagination li a,
    .pagination li span {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        color: #4a5568;
        font-size: 0.875rem;
        text-decoration: none;
    }

    .pagination li.active span {
        background-color: #4299e1;
        color: white;
        border-color: #4299e1;
    }

    .pagination li.disabled span {
        color: #a0aec0;
        cursor: not-allowed;
    }

    .pagination li a:hover {
        background-color: #f7fafc;
    }

    /* Dropdown styling */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        z-index: 1000;
        display: none;
        min-width: 10rem;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 0.875rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-menu-end {
        right: 0;
        left: auto;
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        color: #1e2125;
        background-color: #f8f9fa;
        text-decoration: none;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: #fff;
        text-decoration: none;
        background-color: #0d6efd;
    }

    .dropdown-divider {
        height: 0;
        margin: 0.5rem 0;
        overflow: hidden;
        border-top: 1px solid #e9ecef;
    }

    /* Avatar styling */
    .avatar {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-online::after {
        content: '';
        position: absolute;
        right: 0;
        bottom: 0;
        width: 25%;
        height: 25%;
        border-radius: 50%;
        border: 2px solid #fff;
        background-color: #0abb87;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
    }

    .avatar-circle {
        border-radius: 50%;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    /* Alignments */
    .d-flex {
        display: flex !important;
    }

    .align-items-center {
        align-items: center !important;
    }

    .ms-3 {
        margin-left: 1rem !important;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    /* Text styles */
    .text-muted {
        color: #6c757d !important;
    }

    .fs-6 {
        font-size: 0.875rem !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    /* Icon stuff */
    .fe {
        font-family: feather !important;
        font-style: normal;
        font-weight: 400;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
    }

    .dropdown-item-icon {
        margin-right: 0.5rem;
    }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">

    <div id="app" class="min-h-screen flex">
        <!-- gọi cái sidebar bên trái từ file sidebar.blade trong conponent -->
        <x-sidebar :active="$active ?? ''" :role="$role ?? ''"></x-sidebar>
        <div class="flex-1 ml-64">
            <header class="bg-white shadow-sm">
                <div class="px-4 py-3 flex justify-between items-center">
                    <h1 class="text-xl font-semibold">@yield('page-heading', 'Dashboard')</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Dropdown thông báo -->
                        <!-- x-data	Khởi tạo biến và trạng thái
                        x-text	Hiển thị giá trị biến ra HTML
                        x-show	Ẩn/hiện phần tử dựa vào điều kiện
                        x-init	Chạy code khi khối đó được load -->
                        <div class="relative" x-data="notificationData()" x-init="init()">
                            <button @click="goToNotificationsPage"
                                class="relative p-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                                <!-- Hình cái chuông -->
                                <i class="fas fa-bell text-xl"></i>

                                <!-- Số lượng chưa đọc -->
                                <span x-show="unreadCount > 0" x-transition
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
                                    x-text="unreadCount"></span>
                            </button>
                        </div>

                        <!-- có hàm js Dropdown -->
                        <div class="dropdown">
                            <!-- cái ảnh đại diện admin  các class dưới là những cái tự định nghĩa làm nút xanh -->
                            <a href="#" class="avatar avatar-sm avatar-online dropdown-toggle" role="button"
                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ session('anh_dai_dien') }}" alt="avatar" class="avatar-img rounded-circle">
                            </a>
                            <!-- khi bấm vào cái ảnh admin nó sẽ nhảy xuống thông tin-->
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-circle">
                                            <img src="{{ session('anh_dai_dien') }}" alt="avatar" class="avatar-img">
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">
                                                Xin chào,
                                                {{ session('user_full_name') ?? session('ten_dang_nhap') ?? 'Người dùng' }}!
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <hr class="dropdown-divider">
                                <!-- chuyển hướng tới trang vai trò thích hợp -->
                                @php
                                $profileRoute = '';
                                if (session('vai_tro') == 'admin') {
                                $profileRoute = route('admin.profile.index');
                                } elseif (session('vai_tro') == 'giao_vien') {
                                $profileRoute = route('giao-vien.profile.index');
                                } elseif (session('vai_tro') == 'tro_giang') {
                                $profileRoute = route('tro-giang.profile.index');
                                } elseif (session('vai_tro') == 'hoc_vien') {
                                $profileRoute = route('hoc-vien.profile.index');
                                }
                                @endphp

                                <a class="dropdown-item" href="{{ $profileRoute }}">
                                    <i class="fe fe-user dropdown-item-icon"></i> Thông tin cá nhân
                                </a>

                                <hr class="dropdown-divider">


                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fe fe-log-out dropdown-item-icon"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-6">

                @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    window.vaiTroHienTai = "{{ session('vai_tro') }}";
    </script>
    <script>
    function notificationData() {
        return {
            // tạo cái biến lượng thông báo lượng thông báo chưa đọc. 
            unreadCount: 0,

            // Hàm khởi tạo, tự động gọi updateUnreadCount mỗi 30 giây
            init() {
                this.updateUnreadCount(); // Cập nhật lần đầu
                setInterval(() => {
                    this.updateUnreadCount(); // cập nhật sau 30s30s
                }, 30000);
            },
            // Hàm lấy số lượng thông báo chưa đọc từ API
            updateUnreadCount() {
                fetch('/api/notifications/unread-count') // Gửi request đến API
                    .then(response => response.json()) // Chuyển response sang JSON
                    .then(data => {
                        if (data.success) { // Kiểm tra nếu trả về thành công
                            // Cập nhật số thông báo chưa đọc lên biến unreadCount
                            this.unreadCount = data.unread_count;
                        }
                    })
            },

            // Hàm gọi khi người dùng bấm vào chuông thông báo
            goToNotificationsPage() {
                // Kiểm tra nếu không phải admin thì không cho chuyển trang
                if (window.vaiTroHienTai !== 'admin' && window.vaiTroHienTai !== 'quan_tri') {
                    return;
                }
                // Gửi API để đánh dấu tất cả là đã đọc
                fetch('/api/notifications/mark-all-read', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset đếm thông báo về 0
                            this.unreadCount = 0;

                            // Chuyển trang sau khi xử lý xong
                            window.location.href = '/admin/lien-he';
                        } else {
                            console.error('Không thể đánh dấu đã đọc:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi đánh dấu đã đọc:', error);
                    });
            }
        };
    }
    </script>
    <!-- Dropdown Bootstrap -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý dropdown bootstrap
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdownMenu = this.nextElementSibling;

                // Đóng tất cả các dropdown khác
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle dropdown hiện tại
                dropdownMenu.classList.toggle('show');
            });
        });
    });
    </script>

    <!-- Extra Scripts -->
    @stack('scripts')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>