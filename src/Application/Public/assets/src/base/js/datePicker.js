import moment from 'moment';
import DateRangePicker from 'bootstrap-daterangepicker';

/**
 * Date Picker
 */
export class DatePicker {
    static momentRemoveZone(mObj) {
        return moment.utc(mObj.format('YYYY-MM-DD HH:mm'));
    }

    constructor(selector, options, onSelect) {
        // Set moment locale before anything else
        moment.locale('lv');

        this.selector = $(selector);
        this.onSelect = onSelect;
        this.pickerSettings = {
            minDate: false,
            maxDate: false,
            showWeekNumbers: false,
            timePicker: false,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
        };

        // Extend options with default options
        Object.assign(this.pickerSettings, options);

        // Add format
        if (typeof this.pickerSettings.locale === 'undefined') {
            this.pickerSettings.locale = {};
        }
        if (typeof this.pickerSettings.locale.format === 'undefined') {
            this.pickerSettings.locale.format = (
                this.pickerSettings.timePicker === true
                    ? 'DD.MM.YYYY HH:mm'
                    : 'DD.MM.YYYY'
            );
        }

        this.selector.each((index, el) => {
            const $el = $(el);
            const unixFrom = parseInt($el.data('unix'), 10);
            if (unixFrom) {
                this.pickerSettings.startDate = moment.unix(unixFrom);
            } else if (typeof this.pickerSettings.startDate === 'number') {
                this.pickerSettings.startDate = moment.unix(
                    this.pickerSettings.startDate
                );
            } else if (this.pickerSettings.startDate == null) {
                delete this.pickerSettings.startDate;
            }
            const unixTo = parseInt($el.data('unix_to'), 10);
            if (unixTo) {
                this.pickerSettings.endDate = moment.unix(unixTo);
            } else if (typeof this.pickerSettings.endDate === 'number') {
                this.pickerSettings.endDate = moment.unix(
                    this.pickerSettings.endDate
                );
            } else if (this.pickerSettings.endDate == null) {
                delete this.pickerSettings.endDate;
            }

            $el.on('apply.daterangepicker', (ev, picker) => {
                this.pickerUpdateInput(ev, picker);
            });
            const picker = new DateRangePicker($el, this.pickerSettings, () => {
                $el.trigger('apply.daterangepicker', picker);
            });
        });
    }

    pickerUpdateInput(ev, picker) {
        let start = picker.startDate;
        const end = picker.endDate;

        // Remove time zone if there is no time picker
        if (this.pickerSettings.timePicker === false) {
            start = DatePicker.momentRemoveZone(start);
        }

        // Update first input if this is not the input,
        // otherwise for some reason the value is not set
        let el = picker.element;
        if (picker.element.is(':input') === false) {
            el = $(picker.element).find('input:first');
        }

        if (this.pickerSettings.singleDatePicker === true) {
            el.val(start.format(picker.locale.format));
        } else {
            el.val(
                start.format(picker.locale.format)
                    + picker.locale.separator
                    + end.format(picker.locale.format)
            );
        }

        // Callback
        if (typeof this.onSelect === 'function') {
            this.onSelect.call(this, picker.element, start, end, picker);
        }
    }
}
