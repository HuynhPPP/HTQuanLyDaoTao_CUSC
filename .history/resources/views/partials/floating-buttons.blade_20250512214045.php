<!-- Các nút tròn chức năng ở góc dưới phải -->
<div class="floating-buttons position-fixed bottom-0 end-0 m-4" style="z-index: 9999;">
    <div class="d-flex flex-column gap-3">
        <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg border border-white" 
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="addTimeSlotButton" 
            data-bs-toggle="modal" 
            data-bs-target="#timeSlotModal"
            data-bs-toggle-tooltip="tooltip"
            data-bs-placement="left" 
            title="Thêm khung giờ học">
            <i class="fas fa-clock fa-lg"></i>
        </button>
        <button type="button" class="btn btn-success btn-lg rounded-circle shadow-lg border border-white"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="addAbsenceButton" 
            data-bs-toggle="modal" 
            data-bs-target="#absenceModal"
            data-bs-toggle-tooltip="tooltip"
            data-bs-placement="left" 
            title="Thêm ngày nghỉ">
            <i class="fas fa-plus fa-lg"></i>
        </button>
        <button type="button" class="btn btn-warning btn-lg rounded-circle shadow-lg border border-white"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="addSelfStudy" 
            data-bs-toggle="modal" 
            data-bs-target="#SelfStudyModal"
            data-bs-toggle-tooltip="tooltip"
            data-bs-placement="left" 
            title="Thêm ngày tự học">
            <i class="fa-brands fa-leanpub fa-lg"></i>
        </button>
        <button type="button" class="btn btn-info btn-lg rounded-circle shadow-lg border border-white"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="editTKB" 
            data-bs-toggle="modal" 
            data-bs-target="#EditTKBModal"
            data-bs-toggle-tooltip="tooltip"
            data-bs-placement="left" 
            title="Chỉnh sửa thời gian khai giảng">
            <i class="fa-regular fa-calendar fa-lg"></i>
        </button>
    </div>
</div>

<style>
    .floating-buttons {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
    }

    .floating-buttons .btn {
        opacity: 1 !important;
        visibility: visible !important;
        display: flex !important;
    }

    @media (max-width: 768px) {
        .floating-buttons {
            bottom: 10px !important;
            right: 10px !important;
        }
        .btn-lg {
            width: 40px !important;
            height: 40px !important;
        }
        .fa-lg {
            font-size: 1rem !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo tất cả tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle-tooltip="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Đảm bảo các nút luôn hiển thị
    const floatingButtons = document.querySelector('.floating-buttons');
    if (floatingButtons) {
        floatingButtons.style.display = 'block';
        floatingButtons.style.visibility = 'visible';
        floatingButtons.style.opacity = '1';
    }
});
</script> 