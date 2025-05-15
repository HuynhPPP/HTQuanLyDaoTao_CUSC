@extends('layouts.new_app.master')

@section('main-content')

<div class="section" style="height: 57rem">
    <div id="carouselSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="{{ asset('images/banner_cusc_1.png') }}" class="d-block w-100" alt="banner1">
          </div>
          <div class="carousel-item">
            <img src="{{ asset('images/banner_cusc_2.png') }}" class="d-block w-100" alt="banner2">
          </div>
        </div>
      </div>
</div>

<div class="container my-5">
    <h1 class="text-warning text-center">Những thành tựu</h1>
    <ul class="timeline">
      <li class="timeline-item">
        <strong>Năm 2001:</strong> Trường Đại học Cần Thơ làm lễ ký kết hợp tác với Aptech thành lập Trung tâm Đào tạo Lập trình viên Quốc tế MeKong Delta - Aptech.
      </li>
      <li class="timeline-item">
        <strong>Năm 2002:</strong> Trung tâm MeKong Delta - Aptech mở lớp đào tạo Kỹ thuật viên quốc tế với 96 sinh viên cho khóa đầu tiên.
      </li>
      <li class="timeline-item">
        <strong>Năm 2005:</strong> Chương trình Quản trị mạng ACNA được chính thức triển khai, đây là khoá học thứ 2 được Việt hoá, giúp nhiều người có thể tham gia.
      </li>
      <li class="timeline-item">
        <strong>Năm 2008:</strong> CUSC hợp tác với Prometric thành lập trung tâm khảo thí chứng chỉ CNTT quốc tế đầu tiên tại ĐBSCL.
      </li>
      <li class="timeline-item">
        <strong>Năm 2009:</strong> CUSC xây dựng chương trình đào tạo quản trị mạng mang thương hiệu riêng của CUSC là Quản trị mạng i10 và Chuyên gia QTM Cao cấp.
      </li>
      <li class="timeline-item">
        <strong>Năm 2011:</strong> Được sự chấp thuận của Bộ GD&ĐT và trường Đại học Cần Thơ, CUSC nâng tầm đào tạo lên hệ Cao đẳng CNTT chính quy với 2 chuyên ngành Kỹ thuật phần mềm và Công nghệ Đa phương tiện.
      </li>
      <li class="timeline-item">
        <strong>Năm 2012:</strong> Vinh dự nhận giải thưởng “Đơn vị đào tạo xuất sắc nhất” của tập đoàn Aptech tại “Aptech World Leadership Summit”.
      </li>
      <li class="timeline-item">
        <strong>Năm 2013:</strong> Đạt danh hiệu Sao Khuê về Phần mềm Một cửa điện tử và Dịch vụ công trực tuyến xếp hạng 4 sao.
      </li>
      <li class="timeline-item">
        <strong>Năm 2014:</strong> Đạt danh hiệu Sao Khuê hạng 4 sao cho dịch vụ đào tạo CNTT. Nhận hai danh hiệu “Đơn vị đào tạo xuất sắc” và “Đơn vị đạt chất lượng đào tạo xuất sắc nhất” do Aptech Ấn Độ trao tặng.
      </li>
      <li class="timeline-item">
        <strong>Năm 2015:</strong> Đạt danh hiệu Sao Khuê hạng 4 sao cho dịch vụ đào tạo Ứng dụng CNTT trong đổi mới phương pháp giảng dạy và an toàn, an ninh thông tin.
      </li>
    </ul>
  </div>

 @endsection

