<!-- Các nút tròn chức năng ở góc dưới trái -->
<div class="floating-buttons">
    <div class="floating-buttons-container">
        <button type="button" class="floating-button btn-primary" 
            id="addTimeSlotButton" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Thêm khung giờ học"
            data-bs-toggle="modal" 
            data-bs-target="#timeSlotModal">
            <i class="fas fa-clock fa-lg"></i>
        </button>
        <button type="button" class="floating-button btn-success"
            id="addAbsenceButton" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Thêm ngày nghỉ"
            data-bs-toggle="modal" 
            data-bs-target="#absenceModal">
            <i class="fas fa-plus fa-lg"></i>
        </button>
        <button type="button" class="floating-button btn-warning"
            id="addSelfStudy" 
            data-bs-toggle="tooltip" 
            data-bs-placement="right" 
            title="Thêm ngày tự học"
            data-bs-toggle="modal" 
            data-bs-target="#SelfStudyModal">
            <i class="fa-brands fa-leanpub fa-lg"></i>
        </button>
        <button type="button" class="floating-button btn-info"
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
    .floating-buttons {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 9999;
    }

    .floating-buttons-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .floating-button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .floating-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }

    .floating-button:active {
        transform: translateY(0);
    }

    .floating-button i {
        color: white;
        font-size: 1.2rem;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .floating-buttons {
            bottom: 10px;
            left: 10px;
        }

        .floating-button {
            width: 40px;
            height: 40px;
        }

        .floating-button i {
            font-size: 1rem;
        }
    }
</style> 