# HỆ THỐNG PORTFOLIO ĐIỆN TỬ (E-PORTFOLIO)THEO DÕI NĂNG LỰC HỌC TẬP CỦA SINH VIÊN THEO CHUẨN ĐẦU RA

Đồ án này tập trung vào việc phát triển phân hệ **Sinh viên** và **Cố vấn học tập** được cô lập hoàn toàn thành một chương trình chạy độc lập từ hệ thống gốc. 


## 1. Các Chức Năng Hiện Tại

### Phân hệ Sinh viên
*   **Thông tin cá nhân và chương trình đào tạo:** Xem lý lịch cá nhân sinh viên, thông tin niên khóa tuyển sinh, chương trình đào tạo và Cố vấn học tập trực tiếp phụ trách.
*   **Thống kê tổng hợp:** Xem các chỉ số đếm số môn học đã tham gia, số học kỳ tích lũy và tỷ lệ đạt chuẩn đầu ra chương trình (PLO) của bản thân.
*   **Tương tác AI (Gemini Chatbot):** Tích hợp cửa sổ chat bong bóng nổi sử dụng API Gemini hỗ trợ sinh viên trao đổi, giải đáp trực tiếp về các thắc mắc học tập, PLO, CLO của bản thân.

### Phân hệ Cố vấn học tập
*   **Danh sách quản lý:** Hiển thị danh sách các lớp hành chính cố vấn đang phụ trách (bao gồm tên lớp, niên khóa bắt đầu/kết thúc, sĩ số lớp hiện tại, chương trình đào tạo tương ứng).
*   **Thống kê lớp cố vấn:** Đếm tổng số sinh viên thuộc tất cả các lớp phụ trách và tổng số lớp đang quản lý trực tiếp.

---

## 2. Kiến Trúc Mã Nguồn

```text
Portfolio/
├── docs/                     # Tài liệu hướng dẫn sử dụng và báo cáo đồ án
│   └── huong_dan_su_dung.md  # Hướng dẫn chi tiết cài đặt và chạy thử chương trình
├── src/                      # Mã nguồn chương trình (Laravel Project)
│   ├── app/                  # Controllers, Models, Middleware, Providers
│   ├── bootstrap/            # Cấu hình khởi động Laravel
│   ├── config/               # Cấu hình hệ thống (Database, Mail, View,...)
│   ├── database/             # Lược đồ cơ sở dữ liệu (Migrations & Seeders)
│   ├── public/               # Assets tĩnh (CSS, JS, Fonts, Images)
│   ├── resources/            # Giao diện ứng dụng (Views/Blade templates)
│   ├── routes/               # Quản lý đường dẫn (svRoute, coVanRoute, web)
│   └── .env.example          # File cấu hình môi trường mẫu
└── README.md                 # Tài liệu giới thiệu tổng quan đồ án (File này)
```

---

## 3. Hướng Dẫn Khởi Chạy Nhanh

Chi tiết các bước cài đặt và cấu hình cụ thể xem tại tệp tài liệu: **[Hướng dẫn sử dụng](docs/huong_dan_su_dung.md)**.

Tóm tắt các lệnh chạy cơ bản:
1.  Di chuyển vào thư mục nguồn: `cd src`
2.  Cài đặt các thư viện: `composer install`
3.  Cấu hình cơ sở dữ liệu trong file `.env` (Database name: `capabet_portfolio`)
4.  Tạo bảng và nạp dữ liệu mẫu: `php artisan migrate:fresh --seed`
5.  Khởi chạy server nội bộ: `php artisan serve`

---

## 4. Tài Khoản Đăng Nhập Kiểm Thử

*   **Sinh viên:** MSSV `110116006` | Mật khẩu `123`
*   **Cố vấn học tập:** Tài khoản `ptpnam` hoặc `pttmai` | Mật khẩu `123`
