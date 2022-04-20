import { DatePicker } from 'datePicker';

export class Page {
    constructor(options) {
        // Save options
        this.options = options;

        const pageWrapper = $('#page-contents');
        const datePicker = pageWrapper.find('#datepicker');

        new DatePicker(
            datePicker,
            { singleDatePicker: true, autoApply: true },
            (el, start) => {
                const formattedDate = start.format('YYYY-MM-DD');
                window.location.href = `${this.options.methodUrl}${formattedDate}`;
            }
        );

        pageWrapper.on('click', '.time-type-hour', function () {
            const thisObj = $(this);
            pageWrapper.find('.time-type-minute').addClass('d-none');

            if (thisObj.hasClass('is-open')) {
                thisObj.removeClass('is-open');
                return;
            }

            pageWrapper.find('.time-type-hour.is-open').removeClass('is-open');

            thisObj
                .addClass('is-open')
                .nextUntil('.time-type-hour')
                .removeClass('d-none');
        });
    }
}
