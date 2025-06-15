<div class="bg-white border rounded-lg p-4">
    <h3 class="text-xl font-semibold mb-4 text-blue-600">Danh Mục Thống Kê</h3>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('thong-ke.sinh-vien') }}" 
               class="block px-4 py-2 rounded {{ request()->routeIs('thong-ke.sinh-vien') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                Thống Kê Sinh Viên
            </a>
        </li>
        <li>
            <a href="{{ route('thong-ke.diem-so') }}" 
               class="block px-4 py-2 rounded {{ request()->routeIs('thong-ke.diem-so') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                Thống Kê Điểm Số
            </a>
        </li>
        <li>
            <a href="{{ route('thong-ke.hoc-luc') }}" 
               class="block px-4 py-2 rounded {{ request()->routeIs('thong-ke.hoc-luc') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                Phân Loại Học Lực
            </a>
        </li>
        <li>
            <a href="{{ route('thong-ke.khoa-hoc') }}" 
               class="block px-4 py-2 rounded {{ request()->routeIs('thong-ke.khoa-hoc') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                Thống Kê Khóa Học
            </a>
        </li>
    </ul>
</div> 