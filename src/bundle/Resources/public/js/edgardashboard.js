jQuery(function($) {
    flatpickr('.flatpickr input.date-start', {
            defaultDate: new Date(),
            onChange: function (selectedDates, dateStr, instance) {
                flatpickr('.flatpickr.date-end', {
                    defaultDate: dateStr,
                    minDate: dateStr,
                });
            }
        }
    );
    flatpickr('.flatpickr input.date-end', {
            defaultDate: new Date(),
            minDate: $('input.flatpickr.date-start').val(),
        }
    );
});