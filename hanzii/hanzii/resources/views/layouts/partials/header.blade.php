<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">

<!-- Google Fonts: Quicksand -->
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700;900&display=swap" rel="stylesheet">

<!-- Header -->
<header class="fixed top-0 left-0 w-full z-50 bg-white shadow-lg rounded-b-2xl">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWOFTWYGfZEqslu0RiuJrcbBZndoN9JjmvHg&s"
                alt="Logo Tiếng Trung Lý Thú" class="w-14 h-14 object-contain mr-3" />
            <h1
                class="text-2xl md:text-3xl font-extrabold text-[#452189] flex items-center gap-2 drop-shadow font-quicksand custom-header-title">
                Trung Tâm tiếng Trung Lý Thú
            </h1>
        </div>
        <!-- Desktop menu -->
        <nav class="hidden md:flex space-x-8 items-center">
            <a href="{{ route('welcome') }}" class="header-link">Trang chủ</a>
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
            <a href="{{ route('welcome') }}"
                class="text-[#452189] font-bold text-lg py-2 hover:text-[#EE1C25] transition">Trang chủ</a>
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
        background: linear-gradient(90deg, #FFD600, #EE1C25);
        color: #fff;
        font-weight: bold;
        border-radius: 9999px;
        box-shadow: 0 2px 8px 0 #FFD60055;
        transition: all 0.2s;
        outline: none;
        display: inline-block;
    }

    .header-btn:hover {
        background: linear-gradient(90deg, #EE1C25, #FFD600);
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

    .font-quicksand {
        font-family: 'Quicksand', sans-serif;
    }

    .custom-header-title {
        letter-spacing: 1px;
        font-weight: 800;
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