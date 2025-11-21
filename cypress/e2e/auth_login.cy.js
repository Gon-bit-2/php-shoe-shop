describe("Module Xác thực - Chức năng Đăng nhập", () => {
  it("TC-AUTH-09: Đăng nhập thành công với tài khoản đã tạo", () => {
    // 1. Truy cập trang đăng nhập
    cy.visit("http://localhost/shoe-shop/public/login"); // cy.visit("http://localhost/shoe-shop/public/login"): Truy cập trang đăng nhập

    // 2. Nhập Email & Password hợp lệ
    // (Giả sử tài khoản này đã có trong DB hoặc vừa tạo ở bước trước)
    cy.get("#email").type("admin@gmail.com"); // Hoặc email test cố định
    cy.get("#password").type("12345678"); // cy.get("#email").type("admin@gmail.com"): Nhập email, cy.get("#password").type("12345678"): Nhập mật khẩu

    // 3. Click Đăng nhập
    cy.get('button[type="submit"]').click(); //cy.get('button[type="submit"]').click(): bấm vào nút có type="submit" trong form đăng nhập

    // 4. Kiểm tra kết quả
    // - Chuyển hướng về trang chủ
    cy.url().should("eq", "http://localhost/shoe-shop/public/"); // cy.url().should("eq", "http://localhost/shoe-shop/public/"): Kiểm tra URL hiện tại
    // - Header hiển thị menu người dùng (id="user-menu-button")
    cy.get("#user-menu-button").should("be.visible"); // cy.get("#user-menu-button").should("be.visible"): Kiểm tra element có id="user-menu-button" có hiển thị
  });
});
