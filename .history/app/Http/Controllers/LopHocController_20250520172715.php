// ... existing code ...
public function removeSinhVien($malop, $masv)
{
    try {
        $lophoc = LopHoc::findOrFail($malop);
        $sinhvien = SinhVien::findOrFail($masv);
        
        // Xóa sinh viên khỏi lớp học
        DB::table('chitietlophoc')
            ->where('MaLop', $malop)
            ->where('MaSV', $masv)
            ->delete();

        return redirect()->route('lophoc.show', $malop)
            ->with('success', 'Đã xóa sinh viên khỏi lớp thành công');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Có lỗi xảy ra khi xóa sinh viên khỏi lớp');
    }
}
// ... existing code ...