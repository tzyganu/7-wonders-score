$.widget('wonders.selectAll', {
    options: {
        id: '',
        'label': ''
    },
    _create: function () {
        var that = this;
        var checkbox = $('<input />');
        checkbox.attr('type', 'checkbox');
        checkbox.attr('id', this.getHtmlId());
        var label = $('<label></label>');
        label.attr('for', checkbox.attr('id'));
        label.html(this.getLabel());
        var wrapper = $('<div></div>');
        wrapper.addClass('col-lg-3');
        wrapper.append(checkbox);
        wrapper.append(label);
        if ($(this.element).find('option:not(:selected)').length == 0) {
            checkbox.prop('checked', true);
        }
        $(this.element).on('change', function() {
            checkbox.prop('checked', $(this).find('option:not(:selected)').length == 0);
        });
        checkbox.on('change', function() {
            $(that.element).find('option').prop('selected', $(this).prop('checked'));
            $(that.element).trigger('change');
        });
        wrapper.insertAfter($(this.element).parent());
    },
    getHtmlId: function () {
        if (!this.htmlId) {
            if (this.options.id) {
                this.htmlId = this.options.id;
                return this.htmlId;
            }
            this.htmlId = 'checkbox-' + Math.random().toString(36);
        }
        return this.htmlId;
    },
    getLabel: function () {
        return (this.options.label) ? this.options.label : 'Select All/None';
    }
});
