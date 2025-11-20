describe("Module Admin - Truy cập Dashboard", () => {
  it("TC-DASH-03: Admin đăng nhập và xem thống kê", () => {
    // 1. Đăng nhập với tài khoản Admin (Role ID = 1)
    cy.visit("http://localhost/shoe-shop/public/login");
    cy.get("#email").type("admin@gmail.com"); // Email của Admin
    cy.get("#password").type("123456");
    cy.get('button[type="submit"]').click();

    // 2. Truy cập trang Admin
    cy.visit("http://localhost/shoe-shop/public/admin");

    // 3. Kiểm tra tiêu đề Dashboard
    // Dựa trên views/admin/dashBoards/dashboard.php
    cy.get("h1").should("contain", "Tổng quan Kinh doanh");

    // 4. Kiểm tra các thẻ thống kê hiển thị (Doanh thu, Đơn hàng...)
    // Có 4 thẻ thống kê chính trong grid
    cy.get(".grid-cols-4 > div").should("have.length", 4);
  });
});
