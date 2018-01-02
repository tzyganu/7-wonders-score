$.widget('wonders.preset', {
    options: {
        'startElement': '',
        'endElement': '',
        'config': []
    },
    _create: function() {
        var that = this;
        var config = this.getConfig();
        $(this.element).find('option').remove();
        $(this.options.startElement).datepicker({
            dateFormat: "yy-mm-dd"
        });
        $(this.options.endElement).datepicker({
            dateFormat: "yy-mm-dd"
        });
        $(this.element).append($('<option></option>'));
        for (var i = 0; i<config.length; i++) {
            var option = $('<option></option>');
            option.attr('value', config[i].value);
            option.html(config[i].label);
            $(this.element).append(option);
        }
        $(this.element).on('change', function() {
            for (var i = 0; i<config.length; i++) {
                if (config[i].value == $(this).val()) {
                    $(that.options.startElement).val(that.formatDate(config[i].dates.start));
                    $(that.options.endElement).val(that.formatDate(config[i].dates.end));
                }
            }
        });
    },
    formatDate: function (date) {
        if (date instanceof Date) {
            return date.toISOString().slice(0,10);
        }
        return date;
    },

    getDateDaysDiff: function(date, add) {
        var newDate = new Date();
        newDate.setDate(date.getDate() + add);
        return newDate;
    },
    getDateMonthsDiff: function(date, add) {
        var newDate = new Date();
        newDate.setDate(date.getDate());
        newDate.setMonth(date.getMonth() + add);
        return newDate;
    },
    getDayOfWeek: function (dayOfWeek) {
        dayOfWeek = dayOfWeek % 7;
        if (dayOfWeek == 0) {
            dayOfWeek = 7;
        }
        var date = new Date();
        var day = date.getDay();
        return this.getDateDaysDiff(date, dayOfWeek - day);
    },
    getConfig: function () {
        //return this.options.config;
        var now = new Date();
        return [
            {
                'label': 'Today',
                'value': 'today',
                'dates': {
                    'start': now,
                    'end': now
                }
            },
            {
                'label': 'One Week',
                'value': 'one_week',
                'dates': {
                    'start': this.getDateDaysDiff(now, -7),
                    'end': now
                }
            },
            {
                'label': 'This Week',
                'value': 'this_week',
                'dates': {
                    'start': this.getDayOfWeek(1),
                    'end': now
                }
            },
            {
                'label': 'Last Week',
                'value': 'last_week',
                'dates': {
                    'start': this.getDateDaysDiff(this.getDayOfWeek(1), -7),
                    'end': this.getDateDaysDiff(this.getDayOfWeek(0), -7)
                }
            },
            {
                'label': 'One Month',
                'value': 'one_month',
                'dates': {
                    'start': this.getDateMonthsDiff(now, -1),
                    'end': now
                }
            },
            {
                'label': 'This Month',
                'value': 'this_month',
                'dates': {
                    'start': new Date(now.getFullYear(), now.getMonth(), 1),
                    'end': new Date(now.getFullYear(), now.getMonth() + 1, 0),
                }
            },
            {
                'label': 'Last Month',
                'value': 'last_month',
                'dates': {
                    'start': new Date(now.getFullYear(), now.getMonth() - 1, 1),
                    'end': new Date(now.getFullYear(), now.getMonth(), 0)
                }
            },
            {
                'label': 'One Year',
                'value': 'one_year',
                'dates': {
                    'start': this.getDateMonthsDiff(now, -12),
                    'end': now
                }
            },
            {
                'label': 'This Year',
                'value': 'this_year',
                'dates': {
                    'start': new Date(now.getFullYear(), 0, 1),
                    'end': now
                }
            },
            {
                'label': 'Last Year',
                'value': 'last_year',
                'dates': {
                    'start': new Date(now.getFullYear()-1, 0, 1),
                    'end': new Date(now.getFullYear(), 0, 0)
                }
            },
            {
                'label': 'Since the big bang',
                'value': 'forever',
                'dates': {
                    'start': '',
                    'end': ''
                }
            }
        ]
    }
});
