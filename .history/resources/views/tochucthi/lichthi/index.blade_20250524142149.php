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
                                <div id="myEvent"></div>
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
            var calendarEl = document.getElementById('myEvent');
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
                            end: '{{ $lichThi->NgayThi }}T{{ date('H:i', strtotime('+' . $lichThi->ThoiLuong . ' minutes', strtotime($lichThi->GioBatDau))) }}',
                            backgroundColor: '#007bff',
                            borderColor: '#0056b3',
                            textColor: '#fff',
                            extendedProps: {
                                phongThi: '{{ $lichThi->PhongThi }}',
                                hinhThucThi: '{{ $lichThi->HinhThucThi }}',
                                thoiLuong: '{{ $lichThi->ThoiLuong }} phút'
                            }
                        },
                    @endforeach
                ],
                eventDidMount: function(info) {
                    $(info.el).tooltip({
                        title: 'Phòng: ' + info.event.extendedProps.phongThi + 
                              '\nHình thức: ' + info.event.extendedProps.hinhThucThi +
                              '\nThời lượng: ' + info.event.extendedProps.thoiLuong,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            });
            calendar.render();
        });
    </script>
@endsection
