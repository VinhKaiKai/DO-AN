<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hanzii - Trung tâm đào tạo tiếng Trung chất lượng cao</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
    /* Dark mode styles */
    :root {
        --transition-duration: 0.5s;
    }

    .dark {
        color-scheme: dark;
    }

    .dark body {
        background-color: #1a1a1a;
        color: #e5e5e5;
    }

    .dark .bg-white {
        background-color: #2d2d2d !important;
    }

    .dark .text-gray-600 {
        color: #a3a3a3 !important;
    }

    .dark .text-gray-700 {
        color: #d4d4d4 !important;
    }

    .dark .text-gray-800 {
        color: #e5e5e5 !important;
    }

    .dark .text-gray-900 {
        color: #f5f5f5 !important;
    }

    .dark .border-gray-300 {
        border-color: #404040 !important;
    }

    .dark .bg-gray-50 {
        background-color: #262626 !important;
    }

    .dark .bg-gray-800 {
        background-color: #1a1a1a !important;
    }

    .dark .text-gray-400 {
        color: #a3a3a3 !important;
    }

    .dark .shadow-xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2) !important;
    }

    .dark .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
    }

    .dark .bg-gradient-to-br {
        background-image: linear-gradient(to bottom right, #1a1a1a, #2d2d2d) !important;
    }

    .dark .from-yellow-50 {
        --tw-gradient-from: #1a1a1a !important;
    }

    .dark .to-red-50 {
        --tw-gradient-to: #2d2d2d !important;
    }

    /* Theme toggle button styles */
    .theme-toggle,
    #scroll-top-btn,
    #phone-fab {
        width: 56px;
        height: 56px;
        min-width: 56px;
        min-height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .theme-toggle i,
    #scroll-top-btn i,
    #phone-fab i {
        font-size: 26px;
        line-height: 1;
    }

    .theme-toggle {
        position: fixed;
        right: 20px;
        bottom: 184px;
        /* 56*2 + 24*2 + 24 (bottom) */
        z-index: 100;
        background: linear-gradient(45deg, #FFD700, #FFA500);
        border: none;
    }

    #scroll-top-btn {
        position: fixed;
        right: 20px;
        bottom: 104px;
        /* 56 + 24 + 24 (bottom) */
        background: #FFD600;
        color: #fff;
        border: none;
        z-index: 99;
    }

    #phone-fab {
        position: fixed;
        right: 20px;
        bottom: 24px;
        background: #22c55e;
        color: #fff;
        border: none;
        z-index: 98;
    }

    .theme-toggle:hover {
        transform: scale(1.1);
    }

    .theme-toggle i {
        font-size: 24px;
        color: white;
        transition: all 0.3s ease;
    }

    .dark .theme-toggle {
        background: linear-gradient(45deg, #2C3E50, #3498DB);
    }

    .dark .theme-toggle i {
        transform: rotate(360deg);
    }

    /* Smooth transitions for all elements */
    * {
        transition: background-color var(--transition-duration) ease,
            color var(--transition-duration) ease,
            border-color var(--transition-duration) ease,
            box-shadow var(--transition-duration) ease;
    }

    .hero-pattern {
        background-color: #f9fafc;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23e53e3e' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }

    @keyframes float {
        0% {
            transform: translateY(0px) rotate(0deg);
        }

        25% {
            transform: translateY(-20px) rotate(2deg);
        }

        50% {
            transform: translateY(-35px) rotate(0deg);
        }

        75% {
            transform: translateY(-20px) rotate(-2deg);
        }

        100% {
            transform: translateY(0px) rotate(0deg);
        }
    }

    @keyframes float-reverse {
        0% {
            transform: translateY(0px) rotate(0deg);
        }

        25% {
            transform: translateY(20px) rotate(-2deg);
        }

        50% {
            transform: translateY(35px) rotate(0deg);
        }

        75% {
            transform: translateY(20px) rotate(2deg);
        }

        100% {
            transform: translateY(0px) rotate(0deg);
        }
    }

    .float-animation {
        animation: float 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    .float-animation-reverse {
        animation: float-reverse 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }
    </style>
</head>

<body>
    <!-- Theme Toggle Button (bottom right, above FABs) -->
    <button id="theme-toggle" class="theme-toggle" title="Chuyển đổi chế độ sáng/tối">
        <i class="fas fa-sun"></i>
    </button>

    <!-- Thông báo thành công -->
    @if(session('success'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 fixed top-20 right-4 z-50 shadow-lg rounded">
        <div class="flex">
            <div>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white shadow-lg rounded-b-2xl">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWOFTWYGfZEqslu0RiuJrcbBZndoN9JjmvHg&s"
                    alt="Logo Tiếng Trung Lý Thú" class="w-14 h-14 object-contain mr-3" />
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#452189] flex items-center gap-2 drop-shadow">
                    Trung Tâm tiếng Trung Lý Thú
                </h1>
            </div>
            <!-- Desktop menu -->
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="#" class="header-link">Trang chủ</a>
                <a href="#gioi-thieu" class="header-link">Giới thiệu</a>
                <a href="#lien-he" class="header-link">Liên hệ</a>
                <a href="{{ route('login') }}" class="header-link">Đăng nhập</a>
                <a href="{{ route('register') }}" class="header-btn">Đăng ký</a>
            </nav>
            <!-- Mobile hamburger -->
            <button id="mobile-menu-btn"
                class="md:hidden flex items-center px-3 py-2 border rounded text-[#452189] border-[#452189] focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <!-- Mobile menu overlay -->
        <div id="mobile-menu" class="fixed inset-0 bg-black/60 z-50 hidden">
            <div
                class="absolute top-0 right-0 w-2/3 max-w-xs h-full bg-white shadow-lg flex flex-col p-8 gap-6 animate-slide-in">
                <button id="close-mobile-menu" class="self-end mb-4 text-2xl text-[#452189] focus:outline-none"><i
                        class="fas fa-times"></i></button>
                <a href="#" class="text-[#452189] font-bold text-lg py-2 hover:text-[#EE1C25] transition">Trang chủ</a>
                <a href="#gioi-thieu" class="text-[#452189] font-bold text-lg py-2 hover:text-[#EE1C25] transition">Giới
                    thiệu</a>
                <a href="#lien-he" class="text-[#452189] font-bold text-lg py-2 hover:text-[#EE1C25] transition">Liên
                    hệ</a>
                <a href="{{ route('login') }}"
                    class="text-[#452189] font-bold text-lg py-2 hover:text-[#EE1C25] transition">Đăng nhập</a>
                <a href="{{ route('register') }}"
                    class="text-white font-bold text-lg py-2 rounded bg-gradient-to-tr from-yellow-400 to-red-500 shadow hover:from-red-500 hover:to-yellow-400 transition">Đăng
                    ký</a>
            </div>
        </div>
        <style>
        .header-link {
            position: relative;
            color: #374151;
            font-weight: 600;
            padding-bottom: 4px;
            transition: color 0.2s;
        }

        .header-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, #EE1C25, #FFD600);
            transition: width 0.3s;
        }

        .header-link:hover {
            color: #EE1C25;
        }

        .header-link:hover::after {
            width: 100%;
        }

        .header-btn {
            padding: 0.5rem 1.25rem;
            background: linear-gradient(#EE1C25);
            color: #fff;
            font-weight: bold;
            border-radius: 9999px;
            box-shadow: 0 2px 8px 0 #FFD60055;
            transition: all 0.2s;
            outline: none;
            display: inline-block;
        }

        .header-btn:hover {
            background: linear-gradient(#EE1C25);
            color: #fff;
            transform: scale(1.07);
            box-shadow: 0 4px 16px 0 #EE1C2588;
        }

        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease;
        }
        </style>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const closeBtn = document.getElementById('close-mobile-menu');
            btn.addEventListener('click', function() {
                menu.classList.remove('hidden');
            });
            closeBtn.addEventListener('click', function() {
                menu.classList.add('hidden');
            });
            // Ẩn menu khi click ra ngoài vùng menu
            menu.addEventListener('click', function(e) {
                if (e.target === menu) menu.classList.add('hidden');
            });
            // Ẩn menu khi click vào link
            menu.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            });
        });
        </script>
    </header>

    <!-- Hero Section đẹp mắt -->
    <section class="relative py-24 md:py-32 bg-gradient-to-br from-yellow-50 via-white to-red-50 overflow-hidden">

        <div class=" ml-5 text-4xl md:text-8xl font-bold text-[#452189] opacity-30 mt-28 ">永远成功的沉光荣</div>

        <div class="container mx-auto px-4 relative z-10 flex flex-col md:flex-row items-center gap-10">
            <div class="md:w-1/2 text-center md:text-left flex flex-col justify-center items-center md:items-start">
                <h2
                    class="text-4xl md:text-5xl font-extrabold text-[#452189] mb-4  hero-title opacity-0 translate-y-8 transition-all duration-700">
                </h2>
                <p
                    class="text-lg md:text-xl text-gray-700 mb-8 max-w-xl hero-desc opacity-0 translate-y-8 transition-all duration-700">
                    Khám phá cách học tiếng Trung hiện đại, tương tác và hiệu quả với đội ngũ giáo viên chuyên nghiệp và
                    phương pháp đã được chứng minh.</p>
                <div
                    class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full md:w-auto justify-center md:justify-start">
                    <a href="#khoa-hoc"
                        class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-red-500 text-white rounded-full text-lg font-bold shadow-lg hover:from-red-500 hover:to-yellow-400 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-yellow-200 transform hover:scale-105">Khám
                        phá khóa học</a>
                    <a href="#lien-he"
                        class="px-8 py-4 border-2 border-[#452189] text-[#452189] rounded-full text-lg font-bold bg-white shadow hover:bg-[#452189] hover:text-red transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-yellow-200 transform hover:scale-105">Tư
                        vấn miễn phí</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center items-center">
                <div class="relative group">
                    <img src="{{ asset('storage/background.jpg') }}" alt="Lớp học tiếng Trung"
                        class="rounded-2xl shadow-2xl w-full max-w-md transform group-hover:scale-105 group-hover:shadow-3xl transition-all duration-500 hero-img opacity-0 translate-y-8" />
                    <div
                        class="absolute -top-6 -left-6 w-20 h-20 bg-gradient-to-tr from-yellow-400 to-red-400 rounded-full blur-2xl opacity-40 float-animation">
                    </div>
                    <div
                        class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-tr from-red-400 to-yellow-400 rounded-full blur-2xl opacity-30 float-animation-reverse">
                    </div>
                </div>
            </div>
        </div>
        <style>
        @keyframes bg-move {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        .animate-bg-move {
            background: linear-gradient(120deg, #fffbe6 0%, #ffe4e6 100%);
            background-size: 200% 200%;
            animation: bg-move 8s linear infinite alternate;
        }
        </style>
        <script>
        // Hiệu ứng động khi cuộn tới Hero Section
        document.addEventListener('DOMContentLoaded', function() {
            function revealOnScroll(el) {
                var rect = el.getBoundingClientRect();
                var windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top < windowHeight - 60) {
                    el.classList.remove('opacity-0', 'translate-y-8');
                }
            }
            var heroTitle = document.querySelector('.hero-title');
            var heroDesc = document.querySelector('.hero-desc');
            var heroImg = document.querySelector('.hero-img');

            function onScroll() {
                revealOnScroll(heroTitle);
                revealOnScroll(heroDesc);
                revealOnScroll(heroImg);
            }
            window.addEventListener('scroll', onScroll);
            onScroll();
        });
        </script>
    </section>

    <!-- Giới thiệu trung tâm -->
    <section id="gioi-thieu"
        class="py-20 bg-gradient-to-br from-yellow-50 via-white to-red-50 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\" viewBox=\"0 0 60
            60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg
            fill=\"%23EE1C25\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36
            34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6
            4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16 transform hover:scale-105 transition-all duration-500">
                <h2 class="text-4xl md:text-5xl font-bold text-[#452189] mb-4 relative inline-block">
                    Về Hanzii
                    <div
                        class="absolute -bottom-2 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full">
                    </div>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg mt-6">Trung tâm tiếng Trung Hanzii cung cấp chương
                    trình đào tạo
                    chất lượng cao với phương pháp giảng dạy hiện đại và hiệu quả.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500 border border-white/20">
                    <div
                        class="w-20 h-20 bg-gradient-to-tr from-yellow-400 to-red-500 rounded-2xl flex items-center justify-center mb-6 transform rotate-3 hover:rotate-6 transition-transform">
                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-[#452189]">Giáo viên chất lượng</h3>
                    <p class="text-gray-600 leading-relaxed">Đội ngũ giáo viên có kinh nghiệm, nhiệt tình, đa phần đã
                        học tập và sinh
                        sống tại Trung Quốc.</p>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500 border border-white/20">
                    <div
                        class="w-20 h-20 bg-gradient-to-tr from-yellow-400 to-red-500 rounded-2xl flex items-center justify-center mb-6 transform -rotate-3 hover:-rotate-6 transition-transform">
                        <i class="fas fa-book text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-[#452189]">Giáo trình chính thống</h3>
                    <p class="text-gray-600 leading-relaxed">Giáo trình được biên soạn kỹ lưỡng, chuẩn HSK và phù hợp
                        với người Việt Nam
                        học tiếng Trung.</p>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500 border border-white/20">
                    <div
                        class="w-20 h-20 bg-gradient-to-tr from-yellow-400 to-red-500 rounded-2xl flex items-center justify-center mb-6 transform rotate-3 hover:rotate-6 transition-transform">
                        <i class="fas fa-chart-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-[#452189]">Theo dõi tiến độ</h3>
                    <p class="text-gray-600 leading-relaxed">Hệ thống theo dõi tiến độ học tập giúp học viên và phụ
                        huynh nắm rõ kết quả
                        và quá trình học tập.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Liên hệ động -->
    <section class="py-20 bg-white relative overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-br from-yellow-50 via-white to-red-50 opacity-60 pointer-events-none animate-bg-move">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16 opacity-0 translate-y-8 transition-all duration-700" id="contact-title">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Liên hệ với chúng tôi</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Bạn có thắc mắc? Hãy liên hệ với chúng tôi để được tư vấn
                    miễn phí về các khóa học.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-gray-50 p-6 rounded-lg shadow-md text-center group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:animate-bounce">
                        <i class="fas fa-map-marker-alt text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Địa chỉ</h3>
                    <p class="text-gray-600">123 Đường Láng, Đống Đa, Hà Nội</p>
                </div>
                <div
                    class="bg-gray-50 p-6 rounded-lg shadow-md text-center group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:animate-bounce">
                        <i class="fas fa-phone-alt text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Điện thoại</h3>
                    <p class="text-gray-600">+84 123 456 789</p>
                </div>
                <div
                    class="bg-gray-50 p-6 rounded-lg shadow-md text-center group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:animate-bounce">
                        <i class="fas fa-envelope text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Email</h3>
                    <p class="text-gray-600">vinh.tq.63cntt@ntu.edu.vn</p>
                </div>
            </div>
            <div class="mt-12 bg-gray-50 p-8 rounded-lg shadow-md opacity-0 translate-y-8 transition-all duration-700"
                id="lien-he">
                <form action="{{ route('lien-he.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="ho_ten" class="block text-gray-700 mb-2">Họ và tên</label>
                            <input type="text" id="ho_ten" name="ho_ten" value="{{ old('chu_de') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-yellow-300 transition">
                            @error('ho_ten')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('chu_de') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-yellow-300 transition">
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="chu_de" class="block text-gray-700 mb-2">Chủ đề</label>
                        <input type="text" id="chu_de" name="chu_de" value="{{ old('chu_de') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-yellow-300 transition">
                        @error('chu_de')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="noi_dung" class="block text-gray-700 mb-2">Tin nhắn</label>
                        <textarea id="noi_dung" name="noi_dung" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-yellow-300 transition">{{ old('noi_dung') }}</textarea>
                        @error('noi_dung')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-red-500 text-white rounded-lg font-bold shadow-lg hover:from-red-500 hover:to-yellow-400 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-yellow-200">
                        Gửi tin nhắn
                    </button>
                </form>
            </div>
        </div>
        <style>
        @keyframes bg-move {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        .animate-bg-move {
            background-size: 200% 200%;
            animation: bg-move 8s linear infinite alternate;
        }
        </style>
        <script>
        // Hiệu ứng động khi cuộn tới section liên hệ
        document.addEventListener('DOMContentLoaded', function() {
            function revealOnScroll(el) {
                var rect = el.getBoundingClientRect();
                var windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top < windowHeight - 60) {
                    el.classList.remove('opacity-0', 'translate-y-8');
                }
            }
            var contactTitle = document.getElementById('contact-title');
            var contactForm = document.getElementById('lien-he');

            function onScroll() {
                revealOnScroll(contactTitle);
                revealOnScroll(contactForm);
            }
            window.addEventListener('scroll', onScroll);
            onScroll();
        });
        </script>
    </section>

    <!-- Bản đồ -->
    <div class="rounded-lg overflow-hidden shadow-md mt-10 mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4116.993040170576!2d109.19979561080879!3d12.268148929859924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317067ed3a052f11%3A0xd464ee0a6e53e8b7!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBOaGEgVHJhbmc!5e1!3m2!1svi!2s!4v1749150275526!5m2!1svi!2s"
            width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <!-- Nút scroll to top -->
    <button id="scroll-top-btn"
        class="fixed bottom-28 right-6 z-50 bg-yellow-400 hover:bg-yellow-500 text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center animate-shake transition duration-300"
        title="Lên đầu trang">
        <i class="fas fa-arrow-up text-2xl"></i>
    </button>
    <!-- Nút điện thoại lắc lư -->
    <a href="#lien-he" id="phone-fab"
        class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg w-16 h-16 flex items-center justify-center animate-shake transition duration-300"
        title="Liên hệ ngay">
        <i class="fas fa-phone-alt text-3xl"></i>
    </a>
    <style>
    @keyframes shake {
        0% {
            transform: rotate(0deg);
        }

        20% {
            transform: rotate(-15deg);
        }

        40% {
            transform: rotate(10deg);
        }

        60% {
            transform: rotate(-10deg);
        }

        80% {
            transform: rotate(15deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .animate-shake {
        animation: shake 1.2s infinite;
    }
    </style>
    <script>
    // Cuộn mượt khi bấm nút điện thoại hoặc nút lên đầu trang
    document.addEventListener('DOMContentLoaded', function() {
        var fab = document.getElementById('phone-fab');
        if (fab) {
            fab.addEventListener('click', function(e) {
                var target = document.getElementById('lien-he');
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        }
        var scrollBtn = document.getElementById('scroll-top-btn');
        if (scrollBtn) {
            scrollBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
    </script>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Hanzii</h3>
                    <p class="text-gray-400 mb-4">Trung tâm đào tạo tiếng Trung hàng đầu với phương pháp giảng dạy hiện
                        đại và hiệu quả.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Khóa học</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">HSK 1-2 (Sơ cấp)</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">HSK 3-4 (Trung cấp)</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">HSK 5-6 (Cao cấp)</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Tiếng Trung giao tiếp</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Tiếng Trung thương mại</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Hỗ trợ</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Hướng dẫn đăng ký</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Chính sách bảo mật</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-red-500"></i>
                            <span class="text-gray-400">123 Đường Láng, Đống Đa, Hà Nội</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3 text-red-500"></i>
                            <span class="text-gray-400">+84 123 456 789</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-red-500"></i>
                            <span class="text-gray-400">vinh.tq.63cntt@ntu.edu.vn</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Hanzii. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const icon = themeToggle.querySelector('i');

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }

        themeToggle.addEventListener('click', function() {
            // Toggle dark mode class
            document.documentElement.classList.toggle('dark');

            // Toggle icon
            if (document.documentElement.classList.contains('dark')) {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                localStorage.setItem('theme', 'dark');
            } else {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                localStorage.setItem('theme', 'light');
            }
        });
    });
    </script>

</body>

</html>