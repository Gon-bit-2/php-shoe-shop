describe("Module Sản phẩm - Chức năng Tìm kiếm", () => {
  beforeEach(() => {
    cy.visit("http://localhost/shoe-shop/public/");
  });

  it("TC-PROD-03: Tìm kiếm sản phẩm theo tên", () => {
    const keyword = "Nike"; // Từ khóa test (đảm bảo DB có sản phẩm tên Nike)

    // 1. Tìm ô input search trên header và nhập từ khóa
    // Dựa trên file layout/header.php: <input type="search" name="search" ...>
    cy.get('input[name="search"]')
      .should("be.visible")
      .type(`${keyword}{enter}`); // Nhập và nhấn Enter

    // 2. Kiểm tra URL có chứa tham số search
    cy.url().should("include", `?search=${keyword}`);

    // 3. Kiểm tra tiêu đề trang kết quả
    // Dựa trên file views/home/products/filterPage.php
    cy.get("h1").should("contain", `Kết quả tìm kiếm cho "${keyword}"`);

    // 4. Kiểm tra danh sách sản phẩm trả về không rỗng
    cy.get(".grid-cols-1 > div").should("have.length.greaterThan", 0);
  });
});
