describe("Module Đặt hàng - Quy trình Thanh toán (E2E)", () => {
  it("TC-ORDER-04: Đặt hàng thành công với phương thức COD", () => {
    // --- BƯỚC 1: ĐĂNG NHẬP ---
    cy.visit("http://localhost/shoe-shop/public/login");
    cy.get("#email").type("admin@gmail.com"); // Dùng user có sẵn
    cy.get("#password").type("12345678");
    cy.get('button[type="submit"]').click();

    // --- BƯỚC 2: THÊM VÀO GIỎ ---
    cy.visit("http://localhost/shoe-shop/public/product/42"); // Vào SP ID 1
    // Chọn Size/Màu (Giả lập click option đầu tiên)
    cy.get('.option-btn[data-group="Size"]').first().click();
    cy.get('.option-btn[data-group="Màu sắc"]').first().click();
    cy.get("#add-to-cart-btn").click();

    // --- BƯỚC 3: VÀO TRANG CHECKOUT ---
    // Từ giỏ hàng bấm nút "Tiến hành thanh toán"
    cy.get('a[href="/shoe-shop/public/checkout"]').click();

    // --- BƯỚC 4: ĐIỀN THÔNG TIN GIAO HÀNG ---
    // Dựa trên views/home/cart/checkout.php
    cy.get("#customer_name").clear().type("Cypress Tester");
    cy.get("#customer_phone").clear().type("0909123456");
    cy.get("#customer_address").clear().type("123 Duong Test, TP.HCM");

    // Chọn phương thức COD
    cy.get('input[value="cod"]').check();

    // --- BƯỚC 5: XÁC NHẬN ĐƠN HÀNG ---
    cy.get('button[type="submit"]').contains("Xác nhận và Đặt hàng").click();

    // --- BƯỚC 6: KIỂM TRA KẾT QUẢ ---
    // Kiểm tra chuyển hướng sang trang Success
    cy.url().should("include", "/order-success/");
    // Kiểm tra thông báo cảm ơn
    cy.get("h1").should("contain", "Đặt hàng thành công!");
  });
});
