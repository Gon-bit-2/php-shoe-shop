<footer class="bg-black text-white mt-16  bottom-0 w-full">
    <div class="container mx-auto px-6 py-8">
        <div class="text-center">
            <p>&copy; 2025 ShoeShop. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<script>
    // Lấy các phần tử
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    // Kiểm tra xem các phần tử có tồn tại không
    if (userMenuButton && userMenu) {
        // Bắt sự kiện click vào nút avatar
        userMenuButton.addEventListener('click', function() {
            // Thêm/xóa class 'hidden' để ẩn/hiện menu
            userMenu.classList.toggle('hidden');
        });

        // Đóng menu khi click ra ngoài
        window.addEventListener('click', function(e) {
            if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
</script>
