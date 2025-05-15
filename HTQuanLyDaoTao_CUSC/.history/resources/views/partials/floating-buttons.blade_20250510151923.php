<!-- Các nút tròn chức năng ở góc dưới trái -->
<div class="position-fixed bottom-0 start-0 m-4" style="z-index: 9999;">
    <div class="d-flex flex-column gap-3">
        <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg border border-white" 
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="addTimeSlotButton" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Thêm khung giờ học"
            data-bs-toggle="modal" 
            data-bs-target="#timeSlotModal">
            <i class="fas fa-clock fa-lg"></i>
        </button>
        <button type="button" class="btn btn-success btn-lg rounded-circle shadow-lg border border-white"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="addAbsenceButton" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Thêm ngày nghỉ"
            data-bs-toggle="modal" 
            data-bs-target="#absenceModal">
            <i class="fas fa-plus fa-lg"></i>
        </button>
        <button type="button" class="btn btn-warning btn-lg rounded-circle shadow-lg border border-white"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="addSelfStudy" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Thêm ngày tự học"
            data-bs-toggle="modal" 
            data-bs-target="#SelfStudyModal">
            <i class="fa-brands fa-leanpub fa-lg"></i>
        </button>
        <button type="button" class="btn btn-info btn-lg rounded-circle shadow-lg border border-white"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"
            id="editTKB" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Chỉnh sửa thời gian khai giảng"
            data-bs-toggle="modal" 
            data-bs-target="#EditTKBModal">
            <i class="fa-regular fa-calendar fa-lg"></i>
        </button>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .position-fixed {
            bottom: 10px !important;
            left: 10px !important;
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