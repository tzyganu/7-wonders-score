$.widget('wonders.game', {
    /**
     * widget options
     */
    players: {},
    playerIndex: 1,
    options: {
        maxPlayers: 8,
        minPlayers: 3,
        categories: [],
        addPlayerTrigger: '',
        registeredPlayers: [],
        wonders: [],
        sides: []
    },
    _create: function() {
        var that = this;
        $(this.options.addPlayerTrigger).on('click', function(e) {
            e.preventDefault();
            that.addPlayer(true);
            if (!that.canAddPlayer()) {
                $(this).attr('disabled', 'disabled');
            }
        });
        for (var i = 0;i<this.options.minPlayers;i++) {
            this.addPlayer(false);
        }
        $('.skip-category').on('change', function(e) {
            $(that.element).find('td.category-' + $(this).val() + ' input').attr('disabled', this.checked);
            var keys = Object.keys(that.players);
            for (var i in Object.keys(that.players)) {
                that.calculateTotal(that.players[keys[i]]);
            }
        })
    },
    canAddPlayer: function() {
        return (Object.keys(this.players).length < this.options.maxPlayers);
    },
    addPlayer: function (removable) {
        var that = this;
        if (!this.canAddPlayer()) {
            return;
        }
        var tr = $('<tr></tr>');
        tr.attr('id', 'player_row_' + this.playerIndex);
        var td = $('<td></td>');
        td.attr('id', 'player_col_' + this.playerIndex + '_header');
        td.html(this.generatePlayerHeader(this.playerIndex, removable));
        tr.append(td);
        for (var i = 0; i<this.options.categories.length; i++) {
            td = $('<td></td>');
            var categoryId = this.options.categories[i].id;
            td.attr('id', 'player_col_' + this.playerIndex + '_' + categoryId);
            td.addClass('edit-score category-' + categoryId);
            var scoreInput = this.generateScoreInput(this.playerIndex, categoryId, false);
            if ($('#enabled_category_' + categoryId).prop('checked')) {
                scoreInput.prop('disabled', true);
            }
            td.html(scoreInput);
            tr.append(td);
        }
        //total
        td = $('<td></td>');
        td.attr('id', 'player_col_' + this.playerIndex + '_total');
        td.addClass('total');
        td.html(this.generateScoreInput(this.playerIndex, 'total', true));
        tr.append(td);
        var scoringInputs = $(tr).find('.edit-score input');
        for (i = 0; i<scoringInputs.length;i++) {
            $(scoringInputs[i]).on('change', function() {
                that.calculateTotal(this.closest('tr'));
            })
        }
        $(this.element).find('tbody').append(tr);
        this.players[this.playerIndex] = tr;
        $('select').select2();
        this.playerIndex++;
    },
    calculateTotal: function (tr) {
        var scoringInputs = $(tr).find('.edit-score input');
        var sum = 0;
        for (i = 0; i<scoringInputs.length;i++) {
            if (!$(scoringInputs[i]).prop('disabled')) {
                var score = parseInt($(scoringInputs[i]).val());
                if (!isNaN(score)) {
                    sum += score;
                }
            }
        }
        $(tr).find('.total input').val(sum);
    },
    generateSelect: function(name, options, emptyOption) {
        var select = $('<select></select>');
        select.attr('name', name);
        if (emptyOption) {
            var option = $('<option></option>');
            option.attr('value', emptyOption.id);
            option.html(emptyOption.name);
            select.append(option);
        }
        for (i = 0; i<options.length;i++) {
            var o = options[i];
            option = $('<option></option>');
            option.attr('value', o.id);
            option.html(o.name);
            select.append(option);
        }
        return select;
    },
    generatePlayerHeader: function (index, removable) {
        var wrapper = $('<div></div>');

        var line1 = $('<div></div>');
        line1.addClass('form-group');
        var select = this.generateSelect(
            'player[' + index + ']',
            this.options.registeredPlayers,
            {'id':0, 'name':'--New Player--'}
        );

        line1.append(select);

        select.on('change', function() {
            var nameInput = $(this).closest('td').find('input.new-player-name');
            if ($(this).val() != 0) {
                nameInput.hide().prop('disabled', true);
           } else {
                nameInput.show().prop('disabled', false);
           }
        });

        if (removable) {
            var that = this;
            var del = $('<a></a>');
            del.attr('href', '#');
            del.addClass('fa fa-trash pull-right');
            del.on('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to remove player?')) {
                    this.closest('tr').remove();
                    delete that.players[index];
                    $(that.options.addPlayerTrigger).prop('disabled', false);
                }
            });
            line1.append(del);
        }

        wrapper.append(line1);

        var line2 = $('<div></div>');
        line2.addClass('form-group');
        var input = $('<input />');
        input.addClass('new-player-name');
        input.attr('name', 'new_player[' + index + ']');
        input.prop('required', true);
        line2.append(input);
        wrapper.append(line2);

        var line3 = $('<div></div>');
        line3.addClass('form-group');
        var wonder = this.generateSelect(
            'wonder[' + index + ']',
            this.options.wonders,
            {'id': 0, 'name': '--Wonder--'}
        );
        line3.append(wonder);
        var sides = this.generateSelect('side[' + index + ']', this.options.sides, {'id':0, 'name':'Side'});
        line3.append(sides);
        wrapper.append(line3);
        return wrapper;
    },
    generateScoreInput: function (playerIndex, categoryIndex, isTotal) {
        var input = $('<input />');
        input.attr('name', 'score[' + playerIndex + '][' + categoryIndex + ']');
        input.attr('required', 'required');
        input.addClass('form-control');
        if (isTotal) {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'number');
        }
        if (isTotal) {
            input.attr('disabled', 'disabled');
            input.addClass('disabled');
        }
        return input;
    }
});