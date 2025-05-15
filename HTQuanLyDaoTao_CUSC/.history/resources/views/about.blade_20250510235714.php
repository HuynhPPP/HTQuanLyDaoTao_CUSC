@extends('layouts.new_app.master')

@section('main-content')
    <div class="container my-5">

        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="text-primary fw-bold">Giới thiệu về Trung tâm Đào tạo CUSC</h1>
                <p class="lead mt-3">
                    Trung tâm Đào tạo CUSC là đơn vị tiên phong trong lĩnh vực đào tạo Công nghệ Thông tin tại Đồng bằng
                    Sông Cửu Long, với sứ mệnh cung cấp nguồn nhân lực chất lượng cao, đáp ứng nhu cầu phát triển của xã hội
                    hiện đại.
                </p>
            </div>
        </div>
        <div class="row text-center mb-5">
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h5 class="card-title text-success">Sứ mệnh</h5>
                        <p class="card-text">Đào tạo nguồn nhân lực CNTT chất lượng cao, sáng tạo, hội nhập quốc tế.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h5 class="card-title text-info">Tầm nhìn</h5>
                        <p class="card-text">Trở thành trung tâm đào tạo CNTT hàng đầu khu vực và cả nước.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Giá trị cốt lõi</h5>
                        <p class="card-text">Chất lượng - Sáng tạo - Hội nhập - Trách nhiệm xã hội.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mb-4">
            {{-- <div id="carouselSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
          <div class="carousel-inner">
              <div class="carousel-item active">
                  <img src="{{ asset('images/banner_cusc_1.png') }}" class="d-block w-100" alt="banner1">
              </div>
              <div class="carousel-item">
                  <img src="{{ asset('images/banner_cusc_2.png') }}" class="d-block w-100" alt="banner2">
              </div>
          </div>
      </div> --}}
            <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators3" data-slide-to="0" class=""></li>
                    <li data-target="#carouselExampleIndicators3" data-slide-to="1" class=""></li>
                    <li data-target="#carouselExampleIndicators3" data-slide-to="2" class="active"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item">
                        <img class="d-block w-100" src="assets/img/news/img01.jpg" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="assets/img/news/img07.jpg" alt="Second slide">
                    </div>
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="assets/img/news/img08.jpg" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <h2 class="text-warning text-center mb-4">Những thành tựu nổi bật</h2>
        <ul class="timeline">
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2001</span>
                Trường Đại học Cần Thơ làm lễ ký kết hợp tác với Aptech thành lập Trung tâm Đào tạo Lập trình viên Quốc tế
                MeKong Delta - Aptech.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2002</span>
                Trung tâm MeKong Delta - Aptech mở lớp đào tạo Kỹ thuật viên quốc tế với 96 sinh viên cho khóa đầu tiên.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2005</span>
                Chương trình Quản trị mạng ACNA được chính thức triển khai, đây là khoá học thứ 2 được Việt hoá, giúp nhiều
                người có thể tham gia.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2008</span>
                CUSC hợp tác với Prometric thành lập trung tâm khảo thí chứng chỉ CNTT quốc tế đầu tiên tại ĐBSCL.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2009</span>
                CUSC xây dựng chương trình đào tạo quản trị mạng mang thương hiệu riêng của CUSC là Quản trị mạng i10 và
                Chuyên gia QTM Cao cấp.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2011</span>
                Được sự chấp thuận của Bộ GD&ĐT và trường Đại học Cần Thơ, CUSC nâng tầm đào tạo lên hệ Cao đẳng CNTT chính
                quy với 2 chuyên ngành Kỹ thuật phần mềm và Công nghệ Đa phương tiện.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2012</span>
                Vinh dự nhận giải thưởng "Đơn vị đào tạo xuất sắc nhất" của tập đoàn Aptech tại "Aptech World Leadership
                Summit".
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2013</span>
                Đạt danh hiệu Sao Khuê về Phần mềm Một cửa điện tử và Dịch vụ công trực tuyến xếp hạng 4 sao.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2014</span>
                Đạt danh hiệu Sao Khuê hạng 4 sao cho dịch vụ đào tạo CNTT. Nhận hai danh hiệu "Đơn vị đào tạo xuất sắc" và
                "Đơn vị đạt chất lượng đào tạo xuất sắc nhất" do Aptech Ấn Độ trao tặng.
            </li>
            <li class="timeline-item">
                <span class="badge bg-warning me-2">2015</span>
                Đạt danh hiệu Sao Khuê hạng 4 sao cho dịch vụ đào tạo Ứng dụng CNTT trong đổi mới phương pháp giảng dạy và
                an toàn, an ninh thông tin.
            </li>
        </ul>
    </div>
@endsection
