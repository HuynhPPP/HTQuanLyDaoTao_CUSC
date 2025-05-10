<footer class="footer text-white py-5">
    <!-- Logo ở trên, căn giữa -->
    <div class="text-center mb-4">
        <a href="#" class="d-inline-block bg-white rounded-circle p-3 shadow-sm mb-3">
            <img src="{{ asset('images/logo_CTU.png') }}" height="80" alt="logo_cusc" loading="lazy" />
        </a>
        <h5 class="fw-bold mb-3 text-uppercase">TRUNG TÂM CÔNG NGHỆ PHẦN MỀM<br>ĐẠI HỌC CẦN THƠ</h5>
    </div>
    <div class="px-5">
        <div class="row g-4">
            <!-- Tin tức -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-4">Tin tức</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Tin tức hoạt động</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Hoạt động ngoại khóa</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Tin giáo dục & công nghệ</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Thông báo</a>
                    </li>
                </ul>
            </div>
            <!-- Giáo vụ -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-4">Giáo vụ</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Quy định, quyết định</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Biểu mẫu và quy trình</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Hướng dẫn</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none hover-effect">Thời khóa biểu</a>
                    </li>
                </ul>
            </div>
            <!-- Liên hệ -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-4">Liên hệ</h6>
                <ul class="list-unstyled footer-contact">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-location-dot mt-1 me-3"></i>
                        <a href="#" class="text-white text-decoration-none hover-effect" data-bs-toggle="modal" data-bs-target="#address__modal">
                            Khu III, Đại Học Cần Thơ, 01 Lý Tự Trọng, Q. Ninh Kiều, TP. Cần Thơ
                        </a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-envelope me-3"></i>
                        <a href="mailto:cusc@ctu.edu.vn" class="text-white text-decoration-none hover-effect">cusc@ctu.edu.vn</a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-phone me-3"></i>
                        <a href="tel:+842923835581" class="text-white text-decoration-none hover-effect">+84 292 383 5581</a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-print me-3"></i>
                        <a href="tel:+842923731071" class="text-white text-decoration-none hover-effect">+84 292 373 1071</a>
                    </li>
                </ul>
            </div>
            <!-- Mạng xã hội -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-4">Kết nối</h6>
                <div class="d-flex gap-2">
                    <a href="https://www.youtube.com/c/CUSCAPTECHCHANNEL" class="btn btn-outline-light btn-floating" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="https://www.facebook.com/CUSC.CE/" class="btn btn-outline-light btn-floating" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/arena.cantho/" class="btn btn-outline-light btn-floating" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-floating" data-bs-toggle="modal" data-bs-target="#zalo__modal">
                        <img src="{{ asset('images/logo_zalo.png') }}" width="20" height="20" alt="logo_zalo">
                    </a>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center small mt-3">
            © {{ date('Y') }} Trung tâm Công nghệ phần mềm Đại học Cần Thơ
        </div>
    </div>
    <!-- Modal Zalo -->
    <div class="modal fade" id="zalo__modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Zalo Official</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('images/logo_zalo.png') }}" class="img-fluid" alt="QR_CODE">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Address -->
    <div class="modal fade" id="address__modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Địa chỉ Trung tâm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d502482.16163590417!2d105.8074931446125!3d10.290410928246327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0881f9a732075%3A0xfa43fbeb2b00ca73!2sCUSC%20-%20Cantho%20University%20Software%20Center!5e0!3m2!1svi!2s!4v1648733175621!5m2!1svi!2s" 
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
html, body {
    height: 100%;
    margin: 0;
}
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
.footer {
    background-color: #45526e !important;
    width: 100%;
    margin-top: auto;
}
.footer-links a, .footer-contact a {
    transition: all 0.3s ease;
    position: relative;
}
.footer-links a:hover, .footer-contact a:hover {
    color: #0d6efd !important;
    padding-left: 5px;
}
.btn-floating {
    transition: all 0.3s ease;
}
.btn-floating:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.hover-effect {
    position: relative;
}
.hover-effect::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: #0d6efd;
    transition: width 0.3s ease;
}
.hover-effect:hover::after {
    width: 100%;
}
main {
    flex: 1 0 auto;
}
@media (max-width: 768px) {
    .footer .px-5 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    .footer .row > div {
        margin-bottom: 2rem;
    }
}
</style>