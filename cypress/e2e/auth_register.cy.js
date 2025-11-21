describe("Module Xác thực - Chức năng Đăng ký", () => {
  beforeEach(() => {
    // Truy cập trang đăng ký trước mỗi test case
    cy.visit("http://localhost/shoe-shop/public/register");
  });

  it("TC-AUTH-06: Đăng ký thành công với thông tin hợp lệ", () => {
    // Tạo email ngẫu nhiên để tránh lỗi trùng lặp (TC-AUTH-05)
    const randomEmail = `tester_${Date.now()}@gmail.com`;

    // 1. Nhập Họ tên
    cy.get("#fullname").type("Nguyen Van Tester"); // cy.get("#fullname"): Lấy element có id "fullname", type("Nguyen Van Tester"): Nhập giá trị "Nguyen Van Tester" vào element

    // 2. Nhập Email
    cy.get("#email").type(randomEmail); // cy.get("#email"): Lấy element có id "email", type(randomEmail): Nhập giá trị randomEmail vào element

    // 3. Nhập Mật khẩu & Xác nhận
    cy.get("#password").type("12345678"); // cy.get("#password"): Lấy element có id "password", type("12345678"): Nhập giá trị "12345678" vào element
    cy.get("#confirm_password").type("12345678"); // cy.get("#confirm_password"): Lấy element có id "confirm_password", type("12345678"): Nhập giá trị "12345678" vào element

    // 4. Click nút Đăng ký
    cy.get('button[type="submit"]').click(); // cy.get('button[type="submit"]').click(): Click nút có type="submit"

    // 5. Kiểm tra kết quả mong đợi
    // - URL chuyển về trang login
    cy.url().should("include", "/login"); // cy.url(): Kiểm tra URL hiện tại , should("include"): Kiểm tra URL chứa chuỗi "/login"
    // - Hiển thị thông báo thành công (class alert-success từ file views/login.php)
    cy.get(".alert-success").should("contain", "Đăng ký thành công"); // cy.get(".alert-success"): Lấy element có class "alert-success", should("contain"): Kiểm tra element chứa chuỗi "Đăng ký thành công"
  });

  it("TC-AUTH-04: Đăng ký thất bại do mật khẩu không khớp", () => {
    cy.get("#fullname").type("Tester Fail"); // cy.get("#fullname"): Lấy element có id "fullname", type("Tester Fail"): Nhập giá trị "Tester Fail" vào element
    cy.get("#email").type("fail_pass@gmail.com"); // cy.get("#email"): Lấy element có id "email", type("fail_pass@gmail.com"): Nhập giá trị "fail_pass@gmail.com" vào element
    cy.get("#password").type("12345678"); // cy.get("#password"): Lấy element có id "password", type("12345678"): Nhập giá trị "12345678" vào element
    cy.get("#confirm_password").type("654321"); // Khác mật khẩu
    cy.get('button[type="submit"]').click(); // cy.get('button[type="submit"]').click(): Click nút có type="submit"

    // Kiểm tra hiển thị lỗi (class error-message hoặc alert-error)
    cy.get("#confirm-password-error")
      .should("be.visible") // Đảm bảo lỗi hiện lên
      .and("contain.text", "Mật khẩu xác nhận không khớp");
  });
});
