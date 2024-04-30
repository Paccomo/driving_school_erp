import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import rrulePlugin from '@fullcalendar/rrule';
import ltLocale from '@fullcalendar/core/locales/lt';


document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById("calendar");
    let calendar = new Calendar(calendarEl, {
        allDaySlot: false,
        plugins: [timeGridPlugin, rrulePlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        locale: ltLocale,
        selectable: true,
        events: window.calendarEvents,
        eventClick: function (info) {
            handleEventClick(info);
        }
    });

    calendar.render();
});
