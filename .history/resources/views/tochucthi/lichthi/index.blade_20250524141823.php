@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý lịch thi</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lịch Thi</h4>
                            <div class="card-header-action">
                                <a href="{{ route('lichthi.create') }}" class="btn btn-primary">
                                    Tạo lịch thi mới
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fc-overflow">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach ($lichThis as $lichThi)
                        {
                            title: '{{ $lichThi->monHoc->TenMH }} - {{ $lichThi->MaLop }}',
                            start: '{{ $lichThi->NgayThi }}T{{ $lichThi->GioBatDau }}',
                            end: '{{ \Carbon\Carbon::parse($lichThi->NgayThi . ' ' . $lichThi->GioBatDau)->addMinutes($lichThi->ThoiLuong)->format('Y-m-d\TH:i') }}',
                        },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
@endsection
