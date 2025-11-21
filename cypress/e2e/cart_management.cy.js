describe("Module Giỏ hàng - Quản lý sản phẩm", () => {
  beforeEach(() => {
    //login
    cy.visit("http://localhost/shoe-shop/public/login");
    cy.get("#email").type("admin@gmail.com");
    cy.get("#password").type("12345678");
    cy.get('button[type="submit"]').click();
    // 1. Thêm trước 1 sản phẩm vào giỏ để có dữ liệu test
    cy.visit("http://localhost/shoe-shop/public/product/42");
    cy.get('.option-btn[data-group="Size"]').first().click();
    cy.get('.option-btn[data-group="Màu sắc"]').first().click();
    cy.get("#add-to-cart-btn").click();
  });

  it("TC-CART-04: Cập nhật số lượng và Xóa sản phẩm", () => {
    // --- BƯỚC 1: CẬP NHẬT SỐ LƯỢNG ---
    // Tìm ô input số lượng trong giỏ hàng (name="quantity")
    cy.get('input[name="quantity"]').clear().type("3");

    // Click nút "Cập nhật" (nút submit trong form update)
    cy.contains("button", "Cập nhật").click();

    // Kiểm tra: Số lượng vẫn giữ là 3 sau khi reload
    cy.get('input[name="quantity"]').should("have.value", "3");

    // --- BƯỚC 2: XÓA SẢN PHẨM ---
    // Click nút Xóa (icon thùng rác hoặc nút svg màu đỏ)
    // Dựa trên code view: button có class text-red-500
    cy.get("button.text-red-500").click();

    // --- BƯỚC 3: KIỂM TRA GIỎ HÀNG TRỐNG ---
    // Kiểm tra hiển thị thông báo giỏ hàng trống
    cy.contains("Giỏ hàng của bạn đang trống").should("be.visible");
  });
});
