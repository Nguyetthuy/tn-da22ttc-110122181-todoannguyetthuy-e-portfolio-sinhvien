# HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG 

 Tài liệu này hướng dẫn chi tiết các bước thiết lập cơ sở dữ liệu và khởi chạy phân hệ Sinh viên & Cố vấn học tập đã được **tối giản hóa tối đa** nằm trong thư mục `src`. 


## Các Bước Cài Đặt Ban Đầu

1.  **Di chuyển vào thư mục nguồn (`src`):**
    Mở Cmder hoặc Terminal tại thư mục `Portfolio` và chạy lệnh:
    ```bash
    cd src
    ```

2.  **Cài đặt các gói thư viện (Vendor):**
    Chạy lệnh Composer để thiết lập môi trường:
    ```bash
    composer install
    ```

3.  **Tạo tệp cấu hình môi trường (.env):**
    Sao chép tệp cấu hình mẫu `.env.example` thành `.env`:
    ```bash
    copy .env.example .env
    ```

4.  **Tạo khóa bảo mật cho ứng dụng:**
    Chạy lệnh sinh mã khóa ứng dụng của Laravel:
    ```bash
    php artisan key:generate
    ```

---

##  Cấu Hình & Khởi Tạo Cơ Sở Dữ Liệu

1.  **Tạo Cơ sở dữ liệu mới:**
    Mở công cụ quản lý cơ sở dữ liệu của bạn (ví dụ: phpMyAdmin, DBeaver, Laragon MySQL) và tạo một database mới tên là `portfolio` với bảng mã `utf8mb4_unicode_ci`.

2.  **Chỉnh sửa tệp `.env`:**
    Mở file `.env` và thiết lập kết nối cơ sở dữ liệu:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=capabet_portfolio
    DB_USERNAME=root
    DB_PASSWORD=YOUR_PASSWORD # Nhập mật khẩu MySQL của bạn
    ```

3.  **Nạp cấu trúc bảng và dữ liệu mẫu:**
    Thực hiện lệnh chạy migration tươi và tự động nạp dữ liệu thử nghiệm:
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Lệnh này sẽ tự động sinh bảng cốt lõi nêu trên và nạp dữ liệu tài khoản mẫu.*

---

## 4. Khởi Chạy Ứng Dụng

Chạy máy chủ cục bộ của Laravel bằng lệnh:
```bash
php artisan serve
```
Địa chỉ truy cập mặc định: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 5. Tài Khoản Thử Nghiệm

Hệ thống đã nạp sẵn dữ liệu mẫu phục vụ cho việc kiểm thử. Bạn có thể sử dụng các thông tin sau để đăng nhập:

### A. Quyền Sinh viên (Quyền 5)
*   **Tài khoản:**
    *   Tên đăng nhập (MSSV): `110116006`
    *   Mật khẩu: `123`
*   **Giao diện:** Xem thông tin cá nhân, lớp học, cố vấn học tập, thống kê số môn học, học kỳ, chuẩn đầu ra (PLO) tích lũy và tương tác với chatbot AI.

### B. Quyền Cố vấn học tập (Quyền 6)
*   **Tài khoản:**
    *   Tên đăng nhập (Username): `ptpnam` hoặc `pttmai`
    *   Mật khẩu: `123`
*   **Giao diện:** Xem danh sách các lớp hành chính đang quản lý cố vấn, sĩ số lớp, niên khóa và thông tin chương trình đào tạo tương ứng.
