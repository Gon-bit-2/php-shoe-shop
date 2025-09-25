<header class="bg-white shadow-md sticky top-0 z-10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/shoe-shop/public/" class="text-2xl font-bold text-gray-800">ShoeShop</a>
        <nav class="hidden md:flex space-x-6 items-center">
            <a href="/shoe-shop/public/" class="text-gray-600 hover:text-indigo-600">Trang Chủ</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600">Sản Phẩm</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600">Giới Thiệu</a>
        </nav>
        <div class="flex items-center space-x-4">
            <a href="#" class="text-gray-600 hover:text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </a>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="relative">
                    <button id="user-menu-button" type="button" class="flex text-sm bg-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Open user menu</span>
                        <svg class="h-8 w-8 rounded-full text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <div id="user-menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu">
                        <div class="px-4 py-2 text-sm text-gray-700">
                            Chào, <strong><?= htmlspecialchars($_SESSION['user']['fullname']) ?></strong>
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Thông tin người dùng</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Giỏ hàng</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Lịch sử mua hàng</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Đổi mật khẩu</a>
                        <div class="border-t border-gray-100"></div>
                        <a href="/shoe-shop/public/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/shoe-shop/public/login" class="text-gray-600 hover:text-indigo-600">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </div>
</header>
