-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th6 29, 2026 lúc 01:07 PM
-- Phiên bản máy phục vụ: 8.4.3
-- Phiên bản PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `capabet_portfolio`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cdr_ctdt`
--

CREATE TABLE `cdr_ctdt` (
  `maCDR_CTDT` int UNSIGNED NOT NULL,
  `maCDR_CTDT_VB` text COLLATE utf8mb4_unicode_ci,
  `tenCDR_CTDT` text COLLATE utf8mb4_unicode_ci,
  `maCT` int UNSIGNED DEFAULT '1',
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cdr_ctdt`
--

INSERT INTO `cdr_ctdt` (`maCDR_CTDT`, `maCDR_CTDT_VB`, `tenCDR_CTDT`, `maCT`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 'ELO1', 'ELO1', 1, 0, NULL, NULL),
(2, 'ELO2', 'ELO2', 1, 0, NULL, NULL),
(3, 'ELO3', 'ELO3', 1, 0, NULL, NULL),
(4, 'ELO4', 'ELO4', 1, 0, NULL, NULL),
(5, 'ELO5', 'ELO5', 1, 0, NULL, NULL),
(6, 'ELO6', 'ELO6', 1, 0, NULL, NULL),
(7, 'ELO7', 'ELO7', 1, 0, NULL, NULL),
(8, 'ELO8', 'ELO8', 1, 0, NULL, NULL),
(9, 'ELO9', 'ELO9', 1, 0, NULL, NULL),
(10, 'ELO10', 'ELO10', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `co_van_lop`
--

CREATE TABLE `co_van_lop` (
  `id` bigint UNSIGNED NOT NULL,
  `maGV` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maLop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayBatDau` date NOT NULL,
  `ngayKetThuc` date DEFAULT NULL,
  `isDelete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `co_van_lop`
--

INSERT INTO `co_van_lop` (`id`, `maGV`, `maLop`, `ngayBatDau`, `ngayKetThuc`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, '1234', 'DA16TT', '2020-09-01', '2026-06-30', 0, NULL, NULL),
(2, '8452', 'DA16TT', '2020-09-01', '2026-06-30', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_dao_tao`
--

CREATE TABLE `ct_dao_tao` (
  `maCT` int UNSIGNED NOT NULL,
  `tenCT` text COLLATE utf8mb4_unicode_ci,
  `maBac` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maCNganh` int UNSIGNED DEFAULT '1',
  `maHe` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maBM` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soQuyetDinh` text COLLATE utf8mb4_unicode_ci,
  `ngayBanHanh` text COLLATE utf8mb4_unicode_ci,
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_dao_tao`
--

INSERT INTO `ct_dao_tao` (`maCT`, `tenCT`, `maBac`, `maCNganh`, `maHe`, `maBM`, `soQuyetDinh`, `ngayBanHanh`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 'Xét nghiệm', 'ĐH', 1, 'CQ', '2', NULL, NULL, 0, NULL, NULL),
(2, 'Công nghệ thông tin (kỹ sư 8 HK cũ)', 'ĐH', 2, 'CQ', '1', NULL, NULL, 0, NULL, NULL),
(3, 'Công nghệ thông tin (kỹ sư 8 HK mới)', 'ĐH', 2, 'CQ', '1', NULL, NULL, 0, NULL, NULL),
(4, 'Quản trị mạng', 'ĐH', 4, 'CQ', '1', NULL, NULL, 0, NULL, NULL),
(5, 'Hệ thống thông tin quản lý', 'ĐH', 3, 'CQ', '1', NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giangday`
--

CREATE TABLE `giangday` (
  `id` int UNSIGNED NOT NULL,
  `maHocPhan` int UNSIGNED DEFAULT '1',
  `maLop` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maGV` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHKNH` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giang_vien`
--

CREATE TABLE `giang_vien` (
  `maGV` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hoGV` text COLLATE utf8mb4_unicode_ci,
  `tenGV` text COLLATE utf8mb4_unicode_ci,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `isDelete` tinyint(1) DEFAULT '0',
  `maBM` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giang_vien`
--

INSERT INTO `giang_vien` (`maGV`, `hoGV`, `tenGV`, `username`, `email`, `isDelete`, `maBM`, `created_at`, `updated_at`) VALUES
('1234', 'Phan', 'Thị Phương Nam', 'ptpnam', NULL, 0, '1', NULL, NULL),
('2020', 'Lê', 'Hồng Phong', 'xetnghiem', NULL, 0, '2', NULL, NULL),
('8452', 'Phạm', 'Thị Trúc Mai', 'pttmai', NULL, 0, '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hocky_namhoc`
--

CREATE TABLE `hocky_namhoc` (
  `maHKNH` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenHKNH` text COLLATE utf8mb4_unicode_ci,
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hocky_namhoc`
--

INSERT INTO `hocky_namhoc` (`maHKNH`, `tenHKNH`, `isDelete`, `created_at`, `updated_at`) VALUES
('HK1-2022-2023', '2022-2023', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoa_tuyensinh`
--

CREATE TABLE `khoa_tuyensinh` (
  `maKhoaTuyenSinh` int UNSIGNED NOT NULL,
  `namTS` text COLLATE utf8mb4_unicode_ci,
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khoa_tuyensinh`
--

INSERT INTO `khoa_tuyensinh` (`maKhoaTuyenSinh`, `namTS`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, '2021-2022', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoa_tuyensinh_ct_daotao`
--

CREATE TABLE `khoa_tuyensinh_ct_daotao` (
  `id` int UNSIGNED NOT NULL,
  `maKhoaTuyenSinh` int UNSIGNED DEFAULT '12',
  `maCT` int UNSIGNED DEFAULT '12',
  `isDelete` tinyint(1) DEFAULT '0',
  `maCDR_CTDT` int UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khoa_tuyensinh_ct_daotao`
--

INSERT INTO `khoa_tuyensinh_ct_daotao` (`id`, `maKhoaTuyenSinh`, `maCT`, `isDelete`, `maCDR_CTDT`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 1, NULL, NULL),
(2, 1, 1, 0, 2, NULL, NULL),
(3, 1, 1, 0, 3, NULL, NULL),
(4, 1, 1, 0, 4, NULL, NULL),
(5, 1, 1, 0, 5, NULL, NULL),
(6, 1, 1, 0, 6, NULL, NULL),
(7, 1, 1, 0, 7, NULL, NULL),
(8, 1, 1, 0, 8, NULL, NULL),
(9, 1, 1, 0, 9, NULL, NULL),
(10, 1, 1, 0, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop_hanh_chinh`
--

CREATE TABLE `lop_hanh_chinh` (
  `maLop` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenLop` text COLLATE utf8mb4_unicode_ci,
  `maKhoaTuyenSinh` int UNSIGNED DEFAULT '12',
  `maCT` int UNSIGNED DEFAULT '12',
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lop_hanh_chinh`
--

INSERT INTO `lop_hanh_chinh` (`maLop`, `tenLop`, `maKhoaTuyenSinh`, `maCT`, `isDelete`, `created_at`, `updated_at`) VALUES
('DA16TT', 'ĐH Công nghệ thông tin 2016', 1, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2020_11_24_062709_create_0_users_table', 1),
(2, '2020_11_24_062940_create_9_ct_dao_tao_table', 1),
(3, '2020_11_24_063424_create_12_khoa_tuyensinh_table', 1),
(4, '2020_11_24_063426_create_15_cdr_ctdt_table', 1),
(5, '2020_11_24_063427_create_18_khoa_tuyensinh_ct_daotao_table', 1),
(6, '2020_11_24_063428_create_19_hocky_namhoc_table', 1),
(7, '2020_11_24_063551_create_25_giang_vien_table', 1),
(8, '2020_11_24_063614_create_26_lop_table', 1),
(9, '2020_11_24_063849_create_28_giang_day_table', 1),
(10, '2020_11_24_065556_create_36_sinh_vien_table', 1),
(11, '2026_05_12_075636_create_thongke_plo_sinhvien_table', 1),
(12, '2026_05_17_021246_add_ty_le_dong_gop_to_thongke_plo_sinhvien_table', 1),
(13, '2026_05_18_015904_create_co_van_lop_table', 1),
(14, '2026_05_29_015257_add_ty_le_dg_hocky_to_thongke_plo_sinhvien_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sinh_vien`
--

CREATE TABLE `sinh_vien` (
  `maSSV` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HoSV` text COLLATE utf8mb4_unicode_ci,
  `TenSV` text COLLATE utf8mb4_unicode_ci,
  `Phai` text COLLATE utf8mb4_unicode_ci,
  `NgaySinh` text COLLATE utf8mb4_unicode_ci,
  `maLop` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sinh_vien`
--

INSERT INTO `sinh_vien` (`maSSV`, `HoSV`, `TenSV`, `Phai`, `NgaySinh`, `maLop`, `username`, `isDelete`, `created_at`, `updated_at`) VALUES
('110116006', 'Hứa Thanh', 'Bình', '\"Nam\"', '\"19/01/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116011', 'Phạm Long', 'Đĩnh', '\"Nam\"', '\"18/05/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116018', 'Phạm Nhựt', 'Duy', '\"Nam\"', '\"04/01/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116034', 'Lê Hồ Anh', 'Khoa', '\"Nam\"', '\"19/05/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116042', 'Huỳnh Châu Thế', 'Mỹ', '\"Nam\"', '\"18/09/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116044', 'Cao Mộng', 'Ngân', '\"Nam\"', '\"21/02/1997\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116045', 'Dương Thái', 'Ngọc', '\"Nam\"', '\"14/06/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116046', 'Nguyễn Cao', 'Nhân', '\"Nam\"', '\"05/01/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116051', 'Phạm Thị Yến', 'Nhi', '\"Nam\"', '\"09/01/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116054', 'Thạch Đa', 'Ny', '\"Nam\"', '\"24/08/1996\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116055', 'Trương Sơn Sô', 'Phol', '\"Nam\"', '\"17/03/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116057', 'Lý Gia', 'Quí', '\"Nam\"', '\"29/07/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116086', 'Tưởng Hoàng', 'Tỷ', '\"Nam\"', '\"10/02/1998\"', 'DA16TT', 'admin', 0, NULL, NULL),
('110116087', 'Dư Khánh', 'Vinh', '\"Nam\"', '\"09/07/1998\"', 'DA16TT', 'admin', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongke_plo_sinhvien`
--

CREATE TABLE `thongke_plo_sinhvien` (
  `id` bigint UNSIGNED NOT NULL,
  `maSSV` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHocPhan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maHKNH` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maCDR_CTDT` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ty_le_dat` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ty_le_dong_gop` double(8,2) DEFAULT '0.00' COMMENT 'Ty le dong gop cua mon hoc vao PLO nay',
  `ty_le_dg_hocky` decimal(5,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `password` text COLLATE utf8mb4_unicode_ci,
  `permission` int UNSIGNED DEFAULT '1',
  `isBlock` tinyint(1) DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`username`, `email`, `password`, `permission`, `isBlock`, `isDelete`, `created_at`, `updated_at`) VALUES
('110116006', NULL, '202cb962ac59075b964b07152d234b70', 5, 0, 0, NULL, NULL),
('110116011', NULL, '202cb962ac59075b964b07152d234b70', 5, 0, 0, NULL, NULL),
('admin', NULL, '21232f297a57a5a743894a0e4a801fc3', 1, 0, 0, NULL, NULL),
('ptpnam', NULL, '202cb962ac59075b964b07152d234b70', 6, 0, 0, NULL, NULL),
('pttmai', NULL, '202cb962ac59075b964b07152d234b70', 6, 0, 0, NULL, NULL),
('xetnghiem', NULL, '202cb962ac59075b964b07152d234b70', 4, 0, 0, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cdr_ctdt`
--
ALTER TABLE `cdr_ctdt`
  ADD PRIMARY KEY (`maCDR_CTDT`),
  ADD KEY `cdr_ctdt_mact_foreign` (`maCT`);

--
-- Chỉ mục cho bảng `co_van_lop`
--
ALTER TABLE `co_van_lop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `co_van_lop_magv_foreign` (`maGV`),
  ADD KEY `co_van_lop_malop_foreign` (`maLop`);

--
-- Chỉ mục cho bảng `ct_dao_tao`
--
ALTER TABLE `ct_dao_tao`
  ADD PRIMARY KEY (`maCT`);

--
-- Chỉ mục cho bảng `giangday`
--
ALTER TABLE `giangday`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giangday_mahknh_foreign` (`maHKNH`),
  ADD KEY `giangday_malop_foreign` (`maLop`),
  ADD KEY `giangday_magv_foreign` (`maGV`);

--
-- Chỉ mục cho bảng `giang_vien`
--
ALTER TABLE `giang_vien`
  ADD PRIMARY KEY (`maGV`),
  ADD UNIQUE KEY `giang_vien_magv_unique` (`maGV`),
  ADD KEY `giang_vien_username_foreign` (`username`);

--
-- Chỉ mục cho bảng `hocky_namhoc`
--
ALTER TABLE `hocky_namhoc`
  ADD PRIMARY KEY (`maHKNH`);

--
-- Chỉ mục cho bảng `khoa_tuyensinh`
--
ALTER TABLE `khoa_tuyensinh`
  ADD PRIMARY KEY (`maKhoaTuyenSinh`);

--
-- Chỉ mục cho bảng `khoa_tuyensinh_ct_daotao`
--
ALTER TABLE `khoa_tuyensinh_ct_daotao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khoa_tuyensinh_ct_daotao_makhoatuyensinh_foreign` (`maKhoaTuyenSinh`),
  ADD KEY `khoa_tuyensinh_ct_daotao_mact_foreign` (`maCT`),
  ADD KEY `khoa_tuyensinh_ct_daotao_macdr_ctdt_foreign` (`maCDR_CTDT`);

--
-- Chỉ mục cho bảng `lop_hanh_chinh`
--
ALTER TABLE `lop_hanh_chinh`
  ADD PRIMARY KEY (`maLop`),
  ADD UNIQUE KEY `lop_hanh_chinh_malop_unique` (`maLop`),
  ADD KEY `lop_hanh_chinh_mact_foreign` (`maCT`),
  ADD KEY `lop_hanh_chinh_makhoatuyensinh_foreign` (`maKhoaTuyenSinh`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sinh_vien`
--
ALTER TABLE `sinh_vien`
  ADD PRIMARY KEY (`maSSV`),
  ADD UNIQUE KEY `sinh_vien_massv_unique` (`maSSV`),
  ADD KEY `sinh_vien_malop_foreign` (`maLop`);

--
-- Chỉ mục cho bảng `thongke_plo_sinhvien`
--
ALTER TABLE `thongke_plo_sinhvien`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cdr_ctdt`
--
ALTER TABLE `cdr_ctdt`
  MODIFY `maCDR_CTDT` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `co_van_lop`
--
ALTER TABLE `co_van_lop`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `ct_dao_tao`
--
ALTER TABLE `ct_dao_tao`
  MODIFY `maCT` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `giangday`
--
ALTER TABLE `giangday`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khoa_tuyensinh`
--
ALTER TABLE `khoa_tuyensinh`
  MODIFY `maKhoaTuyenSinh` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `khoa_tuyensinh_ct_daotao`
--
ALTER TABLE `khoa_tuyensinh_ct_daotao`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `thongke_plo_sinhvien`
--
ALTER TABLE `thongke_plo_sinhvien`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `cdr_ctdt`
--
ALTER TABLE `cdr_ctdt`
  ADD CONSTRAINT `cdr_ctdt_mact_foreign` FOREIGN KEY (`maCT`) REFERENCES `ct_dao_tao` (`maCT`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `co_van_lop`
--
ALTER TABLE `co_van_lop`
  ADD CONSTRAINT `co_van_lop_magv_foreign` FOREIGN KEY (`maGV`) REFERENCES `giang_vien` (`maGV`) ON DELETE CASCADE,
  ADD CONSTRAINT `co_van_lop_malop_foreign` FOREIGN KEY (`maLop`) REFERENCES `lop_hanh_chinh` (`maLop`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `giangday`
--
ALTER TABLE `giangday`
  ADD CONSTRAINT `giangday_magv_foreign` FOREIGN KEY (`maGV`) REFERENCES `giang_vien` (`maGV`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `giangday_mahknh_foreign` FOREIGN KEY (`maHKNH`) REFERENCES `hocky_namhoc` (`maHKNH`) ON DELETE CASCADE,
  ADD CONSTRAINT `giangday_malop_foreign` FOREIGN KEY (`maLop`) REFERENCES `lop_hanh_chinh` (`maLop`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `giang_vien`
--
ALTER TABLE `giang_vien`
  ADD CONSTRAINT `giang_vien_username_foreign` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `khoa_tuyensinh_ct_daotao`
--
ALTER TABLE `khoa_tuyensinh_ct_daotao`
  ADD CONSTRAINT `khoa_tuyensinh_ct_daotao_macdr_ctdt_foreign` FOREIGN KEY (`maCDR_CTDT`) REFERENCES `cdr_ctdt` (`maCDR_CTDT`) ON DELETE CASCADE,
  ADD CONSTRAINT `khoa_tuyensinh_ct_daotao_mact_foreign` FOREIGN KEY (`maCT`) REFERENCES `ct_dao_tao` (`maCT`) ON DELETE CASCADE,
  ADD CONSTRAINT `khoa_tuyensinh_ct_daotao_makhoatuyensinh_foreign` FOREIGN KEY (`maKhoaTuyenSinh`) REFERENCES `khoa_tuyensinh` (`maKhoaTuyenSinh`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `lop_hanh_chinh`
--
ALTER TABLE `lop_hanh_chinh`
  ADD CONSTRAINT `lop_hanh_chinh_mact_foreign` FOREIGN KEY (`maCT`) REFERENCES `ct_dao_tao` (`maCT`) ON DELETE CASCADE,
  ADD CONSTRAINT `lop_hanh_chinh_makhoatuyensinh_foreign` FOREIGN KEY (`maKhoaTuyenSinh`) REFERENCES `khoa_tuyensinh` (`maKhoaTuyenSinh`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `sinh_vien`
--
ALTER TABLE `sinh_vien`
  ADD CONSTRAINT `sinh_vien_malop_foreign` FOREIGN KEY (`maLop`) REFERENCES `lop_hanh_chinh` (`maLop`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
