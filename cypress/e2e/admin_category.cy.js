describe("Module Admin - Quản lý Danh mục", () => {
  beforeEach(() => {
    // Đăng nhập quyền Admin trước mỗi test
    cy.visit("http://localhost/shoe-shop/public/login");
    cy.get("#email").type("admin@gmail.com"); // Email Admin
    cy.get("#password").type("123456");
    cy.get('button[type="submit"]').click();
  });

  it("TC-CAT-02: Thêm mới danh mục thành công", () => {
    const categoryName = "Giày Test Auto " + Date.now();

    // 1. Vào trang danh sách danh mục
    cy.visit("http://localhost/shoe-shop/public/admin/categories");

    // 2. Click nút "Thêm Danh mục mới"
    cy.contains("Thêm Danh mục mới").click();

    // 3. Điền thông tin (File views/admin/categories/create.php)
    cy.get("#name").type(categoryName);

    // (Bỏ qua upload ảnh để test đơn giản, hoặc có thể thêm nếu cần)

    // Checkbox "Trạng thái" mặc định đã check

    // 4. Click Lưu
    cy.get('button[type="submit"]').contains("Lưu Danh mục").click();

    // 5. Kiểm tra kết quả
    // - Chuyển về trang danh sách
    cy.url().should("include", "/admin/categories");
    // - Tên danh mục mới xuất hiện trong bảng
    cy.contains("td", categoryName).should("be.visible");
  });
});
