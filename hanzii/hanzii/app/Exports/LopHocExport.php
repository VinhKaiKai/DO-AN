<?php

namespace App\Exports;

use App\Models\LopHoc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LopHocExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $lopHocs;

    public function __construct($lopHocs)
    {
        $this->lopHocs = $lopHocs;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->lopHocs;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Mã lớp',
            'Tên lớp',
            'Khóa học',
            'Giáo viên',
            'Trợ giảng',
            'Hình thức học',
            'Lịch học',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Sĩ số hiện tại',
            'Sĩ số tối đa',
            'Trạng thái',
            'Ngày tạo'
        ];
    }

    /**
     * @param mixed $lopHoc
     * @return array
     */
    public function map($lopHoc): array
    {
        // Định dạng trạng thái
        $trangThai = 'Không xác định';
        if ($lopHoc->trang_thai == 'sap_khai_giang') {
            $trangThai = 'Sắp khai giảng';
        } elseif ($lopHoc->trang_thai == 'dang_dien_ra') {
            $trangThai = 'Đang diễn ra';
        } elseif ($lopHoc->trang_thai == 'da_ket_thuc') {
            $trangThai = 'Đã kết thúc';
        }

        // Định dạng hình thức học
        $hinhThucHoc = $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại trung tâm';

        // Đếm số học viên đã xác nhận
        $siSoHienTai = $lopHoc->dangKyHocs()->where('trang_thai', 'da_xac_nhan')->count();

        return [
            $lopHoc->id,
            $lopHoc->ma_lop,
            $lopHoc->ten,
            $lopHoc->khoaHoc->ten ?? 'N/A',
            $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'N/A',
            $lopHoc->troGiang->nguoiDung->ho_ten ?? 'N/A',
            $hinhThucHoc,
            $lopHoc->lich_hoc,
            $lopHoc->ngay_bat_dau ? date('d/m/Y', strtotime($lopHoc->ngay_bat_dau)) : 'N/A',
            $lopHoc->ngay_ket_thuc ? date('d/m/Y', strtotime($lopHoc->ngay_ket_thuc)) : 'N/A',
            $siSoHienTai,
            $lopHoc->so_luong_toi_da,
            $trangThai,
            $lopHoc->tao_luc ? date('d/m/Y H:i', strtotime($lopHoc->tao_luc)) : 'N/A'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
} 