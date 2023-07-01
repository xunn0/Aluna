$(document).ready(function() {
  var calendar = $('#calendar').fullCalendar({
    
    // Configuration options
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    defaultView: 'month',
    editable: true,
    selectable: true,
    events: [
      // Example events
      {
        title: 'Meeting',
        start: '2023-05-01T10:00:00'
      },
      {
        title: 'Lunch',
        start: '2023-05-02T12:30:00'
      },
      {
        title: 'Conference',
        start: '2023-05-03T09:00:00',
        end: '2023-05-05T17:00:00'
      }
    ],
    select: function(start, end) {
      var title = prompt('Event Title:');
      if (title) {
        var startTime = prompt('Event Start Time (HH:mm):');
        var startDate = moment(start).format('YYYY-MM-DD');
        var startDateTime = moment(startDate + ' ' + startTime, 'YYYY-MM-DD HH:mm').format();
        if (startDateTime) {
          var eventData = {
            title: title,
            start: startDateTime,
            end: end
          };
          calendar.fullCalendar('renderEvent', eventData, true);
        } else {
          alert('Invalid start time!');
        }
      }
      calendar.fullCalendar('unselect');
    },
    eventClick: function(event, jsEvent, view) {
      Swal.fire({
        title: 'Select an option',
        showCancelButton: true,
        confirmButtonText: 'Edit',
        cancelButtonText: 'Delete',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return new Promise((resolve) => {
            resolve('edit');
          });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.value === 'edit') {
          var title = prompt('Update Event Title:', event.title);
          if (title) {
            var startTime = prompt('Update Event Start Time (HH:mm):', moment(event.start).format('HH:mm'));
            var startDate = moment(event.start).format('YYYY-MM-DD');
            var startDateTime = moment(startDate + ' ' + startTime, 'YYYY-MM-DD HH:mm').format();
            if (startDateTime) {
              event.title = title;
              event.start = startDateTime;
              calendar.fullCalendar('updateEvent', event);
            } else {
              alert('Invalid start time!');
            }
          } else {
            alert('Invalid title!');
          }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          if (confirm('Are you sure you want to delete this event?')) {
            calendar.fullCalendar('removeEvents', event._id);
          }
        }
      });
    },
    viewRender: function(view) {
      if (view.type === 'agendaDay') {
        setTimeout(function() {
          $('.fc-axis.fc-widget-content.fc-time-grid').each(function() {
            $(this).find('.fc-axis.fc-widget-content').css('color', 'white');
          });
        }, 0);
      }
    }    
  });
  
});
