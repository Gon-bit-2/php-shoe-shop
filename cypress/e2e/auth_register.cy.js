// Đây là kịch bản kiểm thử tự động Giai đoạn 10
// Tương ứng với Test Case: TC-AUTH-001 (Happy Path)

describe("Giai đoạn 10: Kiểm thử Tự động - Chức năng Đăng ký", () => {
  it("TC-AUTH-001: Kiểm tra luồng đăng ký thành công", () => {
    // Tạo một email ngẫu nhiên để đảm bảo TC-AUTH-003 (Trùng email) không xảy ra
    const randomEmail = `tester${Date.now()}@gmail.com`;

    // Bước 1: Truy cập trang đăng ký
    // Chúng ta dùng "cy.visit()" thay vì "Mở trình duyệt"
    // Lưu ý: Đảm bảo XAMPP của bạn đang chạy
    cy.visit("http://localhost/shoe-shop/public/register");

    // Bước 2: Nhập Họ tên
    // - Tìm input có id="fullname"
    cy.get("#fullname").type("Tester Tu Dong");

    // Bước 3: Nhập Email
    // - Tìm input có id="email"
    cy.get("#email").type(randomEmail);

    // Bước 4: Nhập Mật khẩu
    // - Tìm input có id="password"
    cy.get("#password").type("123456");

    // Bước 5: Nhập Xác nhận mật khẩu
    // - Tìm input có id="confirm_password"
    cy.get("#confirm_password").type("123456");

    // Bước 6: Nhấn nút "Đăng ký"
    // - Tìm button có type="submit"
    cy.get('button[type="submit"]').click();

    // --- Kiểm tra Kết quả mong đợi ---

    // Kết quả 1: Hệ thống chuyển hướng đến trang Đăng nhập
    //
    cy.url().should("include", "/shoe-shop/public/login");

    // Kết quả 2: Hiển thị thông báo thành công
    //
    cy.get(".alert-success").should("contain.text", "Đăng ký thành công");
  });
});
