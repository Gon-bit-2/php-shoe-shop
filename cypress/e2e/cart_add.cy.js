describe("Module Giỏ hàng - Thêm sản phẩm", () => {
  beforeEach(() => {
    //login
    cy.visit("http://localhost/shoe-shop/public/login");
    cy.get("#email").type("admin@gmail.com");
    cy.get("#password").type("12345678");
    cy.get('button[type="submit"]').click();
  });
  it("TC-CART-02: Thêm sản phẩm vào giỏ hàng thành công", () => {
    // it: Tạo test case
    // 1. Truy cập trang chi tiết sản phẩm (Ví dụ ID=1)
    // Lưu ý: Cần đảm bảo ID=1 tồn tại và có biến thể trong DB
    cy.visit("http://localhost/shoe-shop/public/product/42"); // cy.visit("http://localhost/shoe-shop/public/product/42"): Truy cập trang chi tiết sản phẩm

    // 2. Chọn Size (Giả sử chọn Size đầu tiên tìm thấy)
    // Dùng selector dựa trên class 'option-btn' và data-group='Size'
    cy.get('.option-btn[data-group="Size"]').first().click(); // cy.get('.option-btn[data-group="Size"]').first().click(): Chọn Size đầu tiên tìm thấy

    // 3. Chọn Màu sắc (Giả sử chọn Màu đầu tiên)
    cy.get('.option-btn[data-group="Màu sắc"]').first().click(); // cy.get('.option-btn[data-group="Màu sắc"]').first().click(): Chọn Màu sắc đầu tiên tìm thấy

    // 4. Nhập số lượng (mặc định là 1, thử đổi thành 2)
    cy.get("#quantity").clear().type("2"); // cy.get("#quantity").clear().type("2"): Nhập số lượng

    // 5. Click "Thêm vào giỏ hàng"
    // Kiểm tra nút không bị disable trước khi click
    cy.get("#add-to-cart-btn").should("not.be.disabled").click(); // cy.get("#add-to-cart-btn").should("not.be.disabled").click(): Click nút "Thêm vào giỏ hàng"

    // 6. Kiểm tra kết quả
    // - Chuyển hướng sang trang giỏ hàng
    cy.url().should("include", "/cart"); // cy.url().should("include", "/cart"): Kiểm tra URL chứa "/cart"
    // - Kiểm tra có sản phẩm trong bảng giỏ hàng
    cy.get(
      ".w-full.lg\\:w-2\\/3 .flex.items-center.justify-between.border-b"
    ).should("have.length.at.least", 1); // Kiểm tra có ít nhất 1 sản phẩm trong bảng giỏ hàng
  });
});
