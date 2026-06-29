<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SVGeminiController extends Controller
{
    /**
     * Hiển thị giao diện Chat dành cho Sinh viên
     */
    public function chatPage()
    {
        return view('sinhvien.gemini');
    }

    /**
     * Xử lý gửi prompt lên Gemini 2.5 Flash kèm theo thông tin bối cảnh học tập thực tế từ CSDL
     */
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'error' => 'Thiếu API Key! Vui lòng cấu hình GEMINI_API_KEY trong file .env.'
            ], 400);
        }

        // 1. Lấy thông tin sinh viên hiện tại từ Session
        $maSSV = Session::get('maSSV');
        
        if (empty($maSSV)) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng đăng nhập hệ thống trước khi chat.'
            ], 401);
        }

        // Truy vấn thông tin cơ bản của sinh viên từ database
        $svInfo = DB::table('sinh_vien')
            ->join('lop_hanh_chinh', 'sinh_vien.maLop', '=', 'lop_hanh_chinh.maLop')
            ->leftJoin('ct_dao_tao', 'lop_hanh_chinh.maCT', '=', 'ct_dao_tao.maCT')
            ->where('sinh_vien.maSSV', $maSSV)
            ->where('sinh_vien.isDelete', false)
            ->select('sinh_vien.*', 'lop_hanh_chinh.tenLop', 'lop_hanh_chinh.maCT', 'lop_hanh_chinh.maKhoaTuyenSinh', 'ct_dao_tao.tenCT')
            ->first();

        if (!$svInfo) {
            return response()->json([
                'success' => false,
                'error' => 'Không tìm thấy thông tin sinh viên tương ứng trên hệ thống.'
            ], 404);
        }

        // 2. Truy vấn dữ liệu Chuẩn đầu ra (PLO) và Học phần chưa đạt
        $danhSachPloGroupText = "";
        $failedCoursesText = "";
        $lowCLOsText = "";
        $unachievedSkillsText = "";
        $tongSoPlo = 0;
        $soPloDat = 0;

        try {
            // Lấy tất cả PLO duy nhất thuộc chương trình đào tạo của khóa tuyển sinh
            $uniquePLOs = DB::table('cdr_ctdt')
                ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
                ->where('khoa_tuyensinh_ct_daotao.maCT', $svInfo->maCT)
                ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $svInfo->maKhoaTuyenSinh)
                ->where('cdr_ctdt.isDelete', false)
                ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
                ->select('cdr_ctdt.maCDR_CTDT', 'cdr_ctdt.maCDR_CTDT_VB', 'cdr_ctdt.tenCDR_CTDT')
                ->distinct()
                ->get();
            
            $tongSoPlo = $uniquePLOs->count();

            // Lấy kết quả tích lũy chuẩn đầu ra thực tế của sinh viên (để tính tỷ lệ đạt từng PLO)
            $ploAchievements = DB::table('thongke_plo_sinhvien')
                ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
                ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
                ->where('thongke_plo_sinhvien.maSSV', $maSSV)
                ->where('khoa_tuyensinh_ct_daotao.maCT', $svInfo->maCT)
                ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $svInfo->maKhoaTuyenSinh)
                ->where('cdr_ctdt.isDelete', false)
                ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
                ->select(
                    'cdr_ctdt.maCDR_CTDT',
                    'cdr_ctdt.maCDR_CTDT_VB',
                    'cdr_ctdt.tenCDR_CTDT',
                    DB::raw('SUM(CASE WHEN thongke_plo_sinhvien.ty_le_dat >= 40 THEN thongke_plo_sinhvien.ty_le_dong_gop ELSE 0 END) as ty_le_dat_tb')
                )
                ->groupBy('cdr_ctdt.maCDR_CTDT', 'cdr_ctdt.maCDR_CTDT_VB', 'cdr_ctdt.tenCDR_CTDT')
                ->get();

            // PLO được coi là ĐẠT nếu tổng tỷ lệ tích lũy của nó >= 50%
            $achievedPLOs = $ploAchievements->filter(function ($item) {
                return $item->ty_le_dat_tb >= 50;
            });
            $soPloDat = $achievedPLOs->count();

            // Lấy tất cả ánh xạ chuẩn đầu ra - nhóm năng lực để phân loại (vì một PLO có thể ánh xạ sang nhiều học phần với các nhóm năng lực khác nhau)
            $ploGroups = DB::table('cdr_ctdt')
                ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
                ->leftJoin('cdr_hocphan_ctdt', function ($join) use ($svInfo) {
                    $join->on('cdr_ctdt.maCDR_CTDT', '=', 'cdr_hocphan_ctdt.maCDR_CTDT')
                         ->where('cdr_hocphan_ctdt.maCT', '=', $svInfo->maCT)
                         ->where('cdr_hocphan_ctdt.isDelete', '=', false);
                })
                ->leftJoin('nhom_cdr_ct_daotao', 'cdr_hocphan_ctdt.maNhomDR', '=', 'nhom_cdr_ct_daotao.maNhomDR')
                ->where('khoa_tuyensinh_ct_daotao.maCT', $svInfo->maCT)
                ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $svInfo->maKhoaTuyenSinh)
                ->where('cdr_ctdt.isDelete', false)
                ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
                ->select('cdr_ctdt.maCDR_CTDT_VB', 'nhom_cdr_ct_daotao.tenNhomDR')
                ->distinct()
                ->get();

            // Tính tỉ lệ đạt theo từng nhóm chuẩn đầu ra
            $groupStats = [];
            foreach (['Kiến thức', 'Kỹ năng', 'Thái độ'] as $groupName) {
                $groupPlos = $uniquePLOs->filter(function ($item) use ($ploGroups, $groupName) {
                    $myGroups = $ploGroups->where('maCDR_CTDT_VB', $item->maCDR_CTDT_VB)->pluck('tenNhomDR')->filter();
                    if ($myGroups->isEmpty()) {
                        return strcasecmp($groupName, 'Kiến thức') === 0;
                    }
                    return $myGroups->contains(function ($val) use ($groupName) {
                        return strcasecmp($val, $groupName) === 0;
                    });
                });

                $groupPloDat = $groupPlos->filter(function ($item) use ($achievedPLOs) {
                    return $achievedPLOs->contains('maCDR_CTDT_VB', $item->maCDR_CTDT_VB);
                });

                $total = $groupPlos->count();
                $achieved = $groupPloDat->count();
                $percentage = $total > 0 ? round(($achieved / $total) * 100, 1) : 100;
                
                $groupStats[$groupName] = [
                    'total' => $total,
                    'achieved' => $achieved,
                    'percentage' => $percentage,
                    'missing' => $groupPlos->filter(function ($item) use ($achievedPLOs) {
                        return !$achievedPLOs->contains('maCDR_CTDT_VB', $item->maCDR_CTDT_VB);
                    })
                ];
            }

            foreach ($groupStats as $name => $stats) {
                $danhSachPloGroupText .= "- Nhóm {$name}: Đã đạt {$stats['achieved']}/{$stats['total']} ({$stats['percentage']}%).\n";
                if ($stats['total'] > $stats['achieved']) {
                    $missingPLOs = [];
                    foreach ($stats['missing'] as $m) {
                        $missingPLOs[] = "  * {$m->maCDR_CTDT_VB}: {$m->tenCDR_CTDT}";
                    }
                    $danhSachPloGroupText .= "  * Các chuẩn đầu ra còn thiếu:\n" . implode("\n", $missingPLOs) . "\n";
                } else {
                    $danhSachPloGroupText .= "  * Đã hoàn thành 100% nhóm chuẩn này.\n";
                }
            }

            // --- Lấy thông tin các học phần chưa đạt và ảnh hưởng đến PLO dựa trên điểm chuẩn đầu ra (ty_le_dat < 40) ---
            $failedPloRecords = DB::table('thongke_plo_sinhvien')
                ->join('hoc_phan', 'thongke_plo_sinhvien.maHocPhan', '=', 'hoc_phan.maHocPhan')
                ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
                ->leftJoin('cdr_hocphan_ctdt', function ($join) use ($svInfo) {
                    $join->on('cdr_ctdt.maCDR_CTDT', '=', 'cdr_hocphan_ctdt.maCDR_CTDT')
                         ->where('cdr_hocphan_ctdt.maCT', '=', $svInfo->maCT)
                         ->where('cdr_hocphan_ctdt.isDelete', '=', false);
                })
                ->leftJoin('nhom_cdr_ct_daotao', 'cdr_hocphan_ctdt.maNhomDR', '=', 'nhom_cdr_ct_daotao.maNhomDR')
                ->where('thongke_plo_sinhvien.maSSV', $maSSV)
                ->where('thongke_plo_sinhvien.ty_le_dat', '<', 40)
                ->where('hoc_phan.isDelete', false)
                ->where('cdr_ctdt.isDelete', false)
                ->select(
                    'hoc_phan.maHocPhan',
                    'hoc_phan.tenHocPhan',
                    'hoc_phan.maHocPhan_VB',
                    'cdr_ctdt.maCDR_CTDT_VB',
                    'cdr_ctdt.tenCDR_CTDT',
                    'nhom_cdr_ct_daotao.tenNhomDR',
                    'thongke_plo_sinhvien.ty_le_dat'
                )
                ->distinct()
                ->get();

            $failedCoursesWithPlos = [];
            if ($failedPloRecords->isNotEmpty()) {
                $groupedByHp = $failedPloRecords->groupBy('maHocPhan');
                foreach ($groupedByHp as $hpId => $records) {
                    $first = $records->first();
                    $fcPlos = [];
                    foreach ($records as $r) {
                        $groupName = $r->tenNhomDR ?? 'Kiến thức';
                        $fcPlos[] = "  * PLO chưa đạt: {$r->maCDR_CTDT_VB} - {$r->tenCDR_CTDT} (Tỉ lệ đạt: {$r->ty_le_dat}%, Thuộc nhóm: {$groupName})";
                    }
                    $failedCoursesWithPlos[] = "- Học phần: {$first->maHocPhan_VB} - {$first->tenHocPhan}\n" . implode("\n", $fcPlos);
                }
            }

            if (empty($failedCoursesWithPlos)) {
                $failedCoursesText = "Chúc mừng! Sinh viên hiện tại không có học phần nào chưa đạt chuẩn đầu ra (PLO tỉ lệ đạt dưới 40%).";
            } else {
                $failedCoursesText = implode("\n", $failedCoursesWithPlos);
            }

            // Lấy danh sách các kỹ năng (PLO thuộc nhóm Kỹ năng) chưa đạt
            $unachievedSkills = $uniquePLOs->filter(function ($item) use ($ploGroups, $achievedPLOs) {
                $myGroups = $ploGroups->where('maCDR_CTDT_VB', $item->maCDR_CTDT_VB)->pluck('tenNhomDR')->filter();
                $isSkill = $myGroups->contains(function ($val) {
                    return strcasecmp($val, 'Kỹ năng') === 0;
                });
                $isAchieved = $achievedPLOs->contains('maCDR_CTDT_VB', $item->maCDR_CTDT_VB);
                return $isSkill && !$isAchieved;
            });
            $unachievedSkillsText = "";
            if ($unachievedSkills->isNotEmpty()) {
                foreach ($unachievedSkills as $us) {
                    $unachievedSkillsText .= "- {$us->maCDR_CTDT_VB}: {$us->tenCDR_CTDT}\n";
                }
            } else {
                $unachievedSkillsText = "Chúc mừng! Sinh viên đã đạt tất cả các chuẩn đầu ra thuộc nhóm Kỹ năng.";
            }

            // Đã loại bỏ thongke_clo_sinhvien
            $lowCLOsText = "Không áp dụng.";

        } catch (\Exception $dbEx) {
            $danhSachPloGroupText = "Lỗi khi truy xuất PLO: " . $dbEx->getMessage();
            $failedCoursesText = "Lỗi khi truy xuất học phần chưa đạt: " . $dbEx->getMessage();
            $lowCLOsText = "Lỗi khi truy xuất CLO thấp: " . $dbEx->getMessage();
            $unachievedSkillsText = "Lỗi khi truy xuất kỹ năng chưa đạt: " . $dbEx->getMessage();
        }

        // 3. Xây dựng bối cảnh (Context) để truyền vào prompt của Gemini
        $context = "Bạn là trợ lý học tập AI của trường Đại học Trà Vinh. Dưới đây là thông tin học tập thực tế và chính xác của sinh viên đang chat với bạn (được truy xuất từ Cơ sở dữ liệu của trường):\n";
        $context .= "- Họ và tên sinh viên: " . ($svInfo->HoSV . ' ' . $svInfo->TenSV) . "\n";
        $context .= "- Mã số sinh viên (MSSV): {$svInfo->maSSV}\n";
        $context .= "- Lớp hành chính: {$svInfo->tenLop}\n";
        $context .= "- Ngành/Chương trình đào tạo: " . ($svInfo->tenCT ?? 'Chưa rõ') . "\n";
        $context .= "- Tình hình đạt chuẩn đầu ra chương trình đào tạo (PLO): Đã hoàn thành {$soPloDat}/{$tongSoPlo} PLO.\n";
        $context .= "\n--- PHÂN TÍCH TỈ LỆ ĐẠT CHUẨN ĐẦU RA (PLO) THEO NHÓM NĂNG LỰC ---\n";
        $context .= $danhSachPloGroupText;
        $context .= "\n--- TÌNH TRẠNG HỌC PHẦN CHƯA ĐẠT VÀ ẢNH HƯỞNG ĐẾN CHUẨN ĐẦU RA ---\n";
        $context .= $failedCoursesText . "\n";
        // Đã loại bỏ thongke_clo_sinhvien khỏi bối cảnh AI
        $context .= "\n--- DANH SÁCH CÁC KỸ NĂNG (PLO THUỘC NHÓM KỸ NĂNG) CHƯA ĐẠT ---\n";
        $context .= $unachievedSkillsText . "\n";
        $context .= "\n--- GIẢI THÍCH 2 BIỂU ĐỒ TRONG VIEW CHUẨN ĐẦU RA (Dashboard Chuẩn đầu ra) ---\n";
        $context .= "Khi sinh viên hỏi giải thích các biểu đồ hoặc xem biểu đồ ở trang chuẩn đầu ra, hãy giải thích như sau:\n";
        $context .= "1. Biểu đồ cột 'Mức độ hoàn thành Chuẩn đầu ra' (ID: ploBarChart):\n";
        $context .= "   - Ý nghĩa: Cho biết phần trăm hoàn thành tích lũy của sinh viên đối với mỗi chuẩn đầu ra chương trình đào tạo (PLO, ví dụ: ELO1, ELO2,...). Càng gần 100% nghĩa là sinh viên càng đạt gần trọn vẹn chuẩn đầu ra đó.\n";
        $context .= "   - Cách đọc: Trục ngang hiển thị các mã chuẩn đầu ra PLO (như ELO1, ELO2). Trục đứng biểu diễn tỷ lệ hoàn thành từ 0% đến 100%. Các cột màu xanh lá cây thể hiện phần trăm tích lũy thực tế hiện tại.\n";
        $context .= "2. Biểu đồ Heatmap 'Tỷ lệ đóng góp CĐR theo Học kỳ' (ID: heatmapChart):\n";
        $context .= "   - Ý nghĩa: Cho biết mức độ đóng góp (tác động) của các môn học trong từng học kỳ cụ thể vào việc tích lũy chuẩn đầu ra PLO.\n";
        $context .= "   - Cách đọc: Trục ngang hiển thị các PLO, trục dọc hiển thị các học kỳ. Mỗi ô chứa một số là tỷ lệ đóng góp (phần trăm). Màu sắc ô càng đậm màu xanh dương thể hiện học kỳ đó đóng góp (tác động) càng lớn cho PLO tương ứng. Các ô nhạt hoặc màu trắng/xám thể hiện học kỳ đó ít hoặc chưa đóng góp cho PLO tương ứng.\n\n";
        $context .= "\nHướng dẫn trả lời:\n";
        $context .= "- Hãy trả lời câu hỏi của sinh viên một cách thân thiện, ngắn gọn, xưng hô 'mình' - 'bạn'.\n";
        $context .= "- Khi sinh viên hỏi về tình hình học tập hoặc chuẩn đầu ra, hãy phân tích cho sinh viên thấy họ đang đạt được bao nhiêu % ở mỗi nhóm (Kiến thức, Kỹ năng, Thái độ), những chuẩn đầu ra nào thuộc nhóm Kỹ năng chưa đạt.\n";
        $context .= "- Hãy chủ động nêu rõ các kỹ năng chưa đạt để sinh viên dễ dàng định hướng cải thiện.\n";
        $context .= "- Nếu sinh viên hỏi về việc giải thích các biểu đồ ở trang chuẩn đầu ra, hãy dùng thông tin giải thích biểu đồ ở trên để giải thích rõ ràng, súc tích và dễ hiểu nhất.\n";
        $context .= "- Tuyệt đối sử dụng dữ liệu bối cảnh ở trên để trả lời. Không được bịa đặt dữ liệu của sinh viên. Nếu câu hỏi nằm ngoài bối cảnh trên, hãy trả lời lịch sự và đề xuất sinh viên liên hệ Phòng đào tạo hoặc Cố vấn học tập.\n\n";

        // 4. Gộp bối cảnh vào câu hỏi của sinh viên
        $promptFull = $context . "Câu hỏi của sinh viên: " . $request->input('prompt');

        $models = ['gemini-2.5-flash', 'gemini-1.5-flash'];
        $lastError = '';
        $statusCode = 500;

        foreach ($models as $model) {
            try {
                // Gọi API Gemini của Google (thử nghiệm gemini-2.5-flash trước, tự động chuyển sang gemini-1.5-flash nếu quá tải)
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $promptFull]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có nội dung phản hồi.';
                    
                    return response()->json([
                        'success' => true,
                        'data' => $generatedText
                    ]);
                }

                $result = $response->json();
                $lastError = $result['error']['message'] ?? $response->body();
                $statusCode = $response->status();

                // Nếu lỗi không phải do quá tải hoặc giới hạn lượt gọi (429/503), thoát khỏi vòng lặp và báo lỗi luôn
                if ($statusCode != 429 && $statusCode != 503 && strpos(strtolower($lastError), 'high demand') === false) {
                    break;
                }

            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                $statusCode = 500;
            }
        }

        return response()->json([
            'success' => false,
            'error' => 'Lỗi API từ Google: ' . $lastError
        ], $statusCode);
    }
}