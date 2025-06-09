@props(['active' => '', 'role' => ''])

<aside class="w-64 bg-gray-800 text-white h-screen fixed left-0 top-0 overflow-y-auto z-50">
    <div class="px-4 py-6">
        <h1 class="text-xl font-semibold mb-6">{{ config('app.name', 'Trung tâm Tiếng Trung') }}</h1>

        <nav class="space-y-1">
            @if($role === 'hoc_vien')
            <a href="{{ route('hoc-vien.lop-hoc.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'lop-hoc' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Lớp học</span>
                </div>
            </a>

            <a href="{{ route('hoc-vien.ket-qua') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'ket-qua' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Kết quả học tập</span>
                </div>
            </a>

            <a href="{{ route('hoc-vien.profile.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'profile' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Thông tin cá nhân</span>
                </div>
            </a>

            @elseif($role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'dashboard' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Trang chủ</span>
                </div>
            </a>

            <a href="{{ route('admin.khoa-hoc.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'khoa-hoc' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Quản lý khóa học</span>
                </div>
            </a>

            <a href="{{ route('admin.lop-hoc.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'lop-hoc' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Quản lý lớp học</span>
                </div>
            </a>

            <a href="{{ route('admin.nguoi-dung.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'nguoi-dung' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Quản lý người dùng</span>
                </div>
            </a>

            <a href="{{ route('admin.thanh-toan-hoc-phi.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'thanh-toan-hoc-phi' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Thanh toán học phí</span>
                </div>
            </a>
            <a href="{{ route('admin.thanh-toan-luong.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'thanh-toan-luong' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Thanh toán lương</span>
                </div>
            </a>

            @elseif($role === 'giao_vien')
            <a href="{{ route('giao-vien.dashboard') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'dashboard' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Trang chủ</span>
                </div>
            </a>

            <a href="{{ route('giao-vien.lop-hoc.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'lop-hoc' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Lớp giảng dạy</span>
                </div>
            </a>

            <a href="{{ route('giao-vien.cham-diem.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'cham-diem-file' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Chấm bài tập</span>
                </div>
            </a>

            @elseif($role === 'tro_giang')

            <a href="{{ route('tro-giang.lop-hoc.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 {{ $active === 'lop-hoc' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <div class="flex items-center space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span>Lớp học</span>
                </div>
            </a>
            @endif



            <form method="POST" action="{{ route('logout') }}" class="mt-10">
                @csrf
                <button type="submit"
                    class="w-full text-left block py-2.5 px-4 rounded transition duration-200 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Đăng xuất</span>
                    </div>
                </button>
            </form>
        </nav>
    </div>
</aside>