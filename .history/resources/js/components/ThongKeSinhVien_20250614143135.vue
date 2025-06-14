<template>
  <div class="thong-ke-sinh-vien">
    <h2 class="text-2xl font-bold mb-4">Thống Kê Sinh Viên Theo Khoa</h2>
    
    <div v-if="loading" class="text-center">
      <p>Đang tải dữ liệu...</p>
    </div>
    
    <div v-else-if="error" class="text-red-500">
      {{ error }}
    </div>
    
    <div v-else>
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-200">
            <th class="border p-2">Mã Khoa</th>
            <th class="border p-2">Tổng Số Lượng</th>
            <th class="border p-2">Nam</th>
            <th class="border p-2">Nữ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(khoa, index) in thongKe" :key="index" class="hover:bg-gray-100">
            <td class="border p-2 text-center">{{ khoa.MaKhoa }}</td>
            <td class="border p-2 text-center">{{ khoa.tong_so_luong }}</td>
            <td class="border p-2 text-center">{{ khoa.nam }}</td>
            <td class="border p-2 text-center">{{ khoa.nu }}</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-4">
        <canvas ref="chartSinhVien" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Chart from 'chart.js/auto';

export default {
  data() {
    return {
      thongKe: [],
      loading: true,
      error: null,
      chart: null
    }
  },
  mounted() {
    this.layThongKeSinhVien();
  },
  methods: {
    async layThongKeSinhVien() {
      try {
        const response = await axios.get('/api/thong-ke/sinh-vien');
        this.thongKe = response.data.data;
        this.loading = false;
        this.$nextTick(() => {
          this.taoBieuDoSinhVien();
        });
      } catch (error) {
        this.error = 'Không thể tải dữ liệu thống kê';
        this.loading = false;
        console.error(error);
      }
    },
    taoBieuDoSinhVien() {
      const ctx = this.$refs.chartSinhVien.getContext('2d');
      
      const labels = this.thongKe.map(khoa => khoa.MaKhoa);
      const tongSoLuong = this.thongKe.map(khoa => khoa.tong_so_luong);
      
      this.chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Tổng Số Sinh Viên',
            data: tongSoLuong,
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Số Lượng Sinh Viên'
              }
            }
          }
        }
      });
    }
  }
}
</script>

<style scoped>
.thong-ke-sinh-vien {
  @apply p-4 bg-white rounded-lg shadow-md;
}
</style> 