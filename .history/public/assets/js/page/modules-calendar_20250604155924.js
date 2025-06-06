"use strict";

$("#myEvent").fullCalendar({
  height: 'auto',
  locale: 'vi', // Thêm locale tiếng Việt
  header: {
    left: 'prev,next today', // Nút điều hướng
    center: 'title', // Tiêu đề
    right: 'month,agendaWeek,agendaDay' // Các chế độ xem
  },
  buttonText: { // Tùy chỉnh văn bản nút
    today: 'Hôm nay',
    month: 'Tháng',
    week: 'Tuần',
    day: 'Ngày',
    list: 'Danh sách'
  },
  editable: true,
//   events: [
//     {
//       title: 'Conference',
//       start: '2018-01-9',
//       end: '2018-01-11',
//       backgroundColor: "#fff",
//       borderColor: "#fff",
//       textColor: '#000'
//     },
//     {
//       title: "John's Birthday",
//       start: '2018-01-14',
//       backgroundColor: "#007bff",
//       borderColor: "#007bff",
//       textColor: '#fff'
//     },
//     {
//       title: 'Reporting',
//       start: '2018-01-10T11:30:00',
//       backgroundColor: "#f56954",
//       borderColor: "#f56954",
//       textColor: '#fff'
//     },
//     {
//       title: 'Starting New Project',
//       start: '2018-01-11',
//       backgroundColor: "#ffc107",
//       borderColor: "#ffc107",
//       textColor: '#fff'
//     },
//     {
//       title: 'Social Distortion Concert',
//       start: '2018-01-24',
//       end: '2018-01-27',
//       backgroundColor: "#000",
//       borderColor: "#000",
//       textColor: '#fff'
//     },
//     {
//       title: 'Lunch',
//       start: '2018-01-24T13:15:00',
//       backgroundColor: "#fff",
//       borderColor: "#fff",
//       textColor: '#000',
//     },
//     {
//       title: 'Company Trip',
//       start: '2018-01-28',
//       end: '2018-01-31',
//       backgroundColor: "#fff",
//       borderColor: "#fff",
//       textColor: '#000',
//     },
//   ]

// });
eventLimit: true, // Giới hạn số sự kiện hiển thị
events: function(start, end, timezone, callback) {
  $.ajax({
    url: '/get-calendar-events',
    dataType: 'json',
    success: function(doc) {
      var events = [];
      $.each(doc, function(i, event) {
        events.push({
          title: event.title,
          start: event.start,
          end: event.end,
          backgroundColor: event.backgroundColor,
          borderColor: event.borderColor,
          textColor: event.textColor,
          description: event.description
        });
      });
      callback(events);
    }
  });
},
eventRender: function(event, element) {
  // Thêm tooltip chi tiết
  element.tooltip({
    title: event.description,
    placement: 'top',
    trigger: 'hover'
  });
}
});