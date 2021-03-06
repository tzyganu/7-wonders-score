Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

$.widget('wonders.game', {
    /**
     * widget options
     */
    players: {},
    playerIndex: 1,
    options: {
        maxPlayers: 8,
        minPlayers: 3,
        defaultPlayers: 7,
        categories: [],
        addPlayerTrigger: '',
        registeredPlayers: [],
        wonders: [],
        sides: [],
        randomizeWondersTrigger: null,
        randomizeSidesTrigger: null
    },
    _create: function() {
        var that = this;
        $(this.options.addPlayerTrigger).on('click', function(e) {
            e.preventDefault();
            that.addPlayer(true);
        });
        var defaultPlayers = this.options.defaultPlayers;
        if (defaultPlayers < this.options.minPlayers) {
            defaultPlayers = this.options.minPlayers;
        }
        if (defaultPlayers > this.options.maxPlayers) {
            defaultPlayers = this.options.maxPlayers;
        }
        for (var i = 0;i<defaultPlayers;i++) {
            this.addPlayer(i>=this.options.minPlayers);
        }
        $('.skip-category').on('change', function(e) {
            $(that.element).find('td.category-' + $(this).val() + ' input').attr('disabled', this.checked);
            var keys = Object.keys(that.players);
            for (var i in Object.keys(that.players)) {
                that.calculateTotal(that.players[keys[i]]);
            }
        });
        if (this.options.randomizeWondersTrigger) {
            this.options.randomizeWondersTrigger.on('click', function() {
                var wonders = that.getValidWonders();
                that.shuffle(wonders);
                var wonderSelects = $(that.element).find('select.wonder-select');
                for (var i = 0; i < wonderSelects.length; i++) {
                    if (typeof wonders[i] === "undefined") {
                        alert('Not enough wonders for all the players! Need more wonders!');
                        break;
                    }
                    $(wonderSelects[i]).val(wonders[i].id).trigger('change');
                }
            });
        }
        if (this.options.randomizeSidesTrigger) {
            this.options.randomizeSidesTrigger.on('click', function() {
                var sides = that.options.sides;
                var sideSelects = $(that.element).find('select.side-select');
                var max = Math.pow(2, sideSelects.length) - 1;
                var min = 0;
                var random = Math.floor(Math.random() * (max - min + 1)) + min;
                random = random.toString(2);
                var totalSides = sideSelects.length;
                var pad = '';
                for (var i = 0;i<totalSides;i++) {
                    pad += '0';
                }
                random = pad.substring(0, pad.length - random.length) + random;
                for (i = 0; i < totalSides; i++) {
                    var side = (typeof random[i] == "undefined") ? 0 : random[i];
                    $(sideSelects[i]).val(sides[side].id).trigger('change');
                }
            });
        }
        if (this.options.wonderGroupsTrigger) {
            this.options.wonderGroupsTrigger.selectAll();
            this.options.wonderGroupsTrigger.on('change', function() {
                $('.wonder-select').each(function(index) {
                    var currentValue = $(this).val();
                    $(this).html('');
                    var dummy = that.generateSelect(
                        'wonder[' + index + ']',
                        that.getValidWonders(),
                        {'id': 0, 'name': '--Wonder--'}
                    );
                    $(this).html(dummy.html());
                    $(this).val(currentValue);
                    $(this).trigger('change');
                });
                // var currentValue = $(this).val();
                //
                // for (var i = 0;i< that.options.wonders.length; i++) {
                //     var diff = $(that.options.wonders[i].groups).filter(currentValue);
                //     var wonders = $('.wonder-select').find('option[value=' + that.options.wonders[i].id + ']');
                //     if (diff.length == 0) {
                //         wonders.remove();
                //     } else if (wonders.length == 0) {
                //         $('.wonder-select').append('<option value="' + that.options.wonders[i].id + '">' + that.options.wonders[i].name + '</option>');
                //     }
                // }
            });
        }
        this.attachValidation();
    },
    attachValidation: function() {
        var that = this;
        window.addEventListener('load', function() {
            var form = $(that.element).closest('form');
            form.on('submit', function(event) {
                if (!that.validateForm(this)) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        }, false);
    },
    getValidWonders: function() {
        var groupTrigger = $(this.options.wonderGroupsTrigger);
        if (groupTrigger.length === 0) {
            return this.options.wonders;
        }
        var validWonders = [];
        var groupsValue = groupTrigger.val();
        groupsValue = groupsValue.map(x => parseInt(x));
        for (var i = 0;i< this.options.wonders.length; i++) {
            var diff = $(this.options.wonders[i].groups).filter(groupsValue);
            if (diff.length !== 0) {
                validWonders.push(this.options.wonders[i]);
            }
        }
        return validWonders;
    },
    validateForm: function(form) {
        //validate wonders
        var wonders = $('.wonder-select');
        var wonderValues = {};
        for (var i = 0;i<wonders.length;i++) {
            var val = $(wonders[i]).val();
            if (!val) {
                continue;
            }
            if (typeof wonderValues[val] != "undefined") {
                alert('You cannot have 2 players play with the same wonder');
                return false;
            }
            wonderValues[val] = val;
        }

        //validate players
        var players = $('.player-select');
        var playerValues = {};
        for (i = 0;i<players.length;i++) {
            val = $(players[i]).val();
            if (!val) {
                continue;
            }
            if (typeof playerValues[val] != "undefined") {
                alert('You cannot have 2 times the same player');
                return false;
            }
            playerValues[val] = val;
        }
        return true;
    },
    shuffle: function (array) {
        var m = array.length, t, i;

        // While there remain elements to shuffle…
        while (m) {
            // Pick a remaining element…
            i = Math.floor(Math.random() * m--);
            // And swap it with the current element.
            t = array[m];
            array[m] = array[i];
            array[i] = t;
        }

        return array;
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
        var tdIndex = $('<td></td>');
        tdIndex.html($(this.element).find('tbody').children().length + 1);
        tr.append(tdIndex);
        var tdPlayer = $('<td></td>');
        tdPlayer.attr('id', 'player_col_' + this.playerIndex + '_header');
        tdPlayer.html(this.generatePlayerHeader(this.playerIndex, removable));
        tr.append(tdPlayer);

        var tdWonder = $('<td></td>');
        tdWonder.attr('id', 'wonder_col_' + this.playerIndex + '_header');
        tdWonder.html(this.generateWonderLine(this.playerIndex));
        tr.append(tdWonder);

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
        if (!that.canAddPlayer()) {
            $(this).attr('disabled', 'disabled');
        }
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

    generateWonderLine: function(index) {
        var line = $('<div></div>');
        line.addClass('form-group');
        var wonder = this.generateSelect(
            'wonder[' + index + ']',
            this.options.wonders,
            {'id': 0, 'name': '--Wonder--'}
        );
        wonder.addClass('wonder-select');
        line.append(wonder);
        var sides = this.generateSelect('side[' + index + ']', this.options.sides, {'id':0, 'name':'Side'});
        sides.addClass('side-select');
        line.append(sides);
        return line;
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
        select.addClass('player-select');

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
            del.addClass('fa fa-trash');
            del.on('click', function (e) {
                e.preventDefault();
                if (index < that.options.minPlayers) {
                    return false;
                }
                if (confirm('Are you sure you want to remove player?')) {
                    this.closest('tr').remove();
                    delete that.players[index];
                    $(that.options.addPlayerTrigger).prop('disabled', false);
                    var trs = $(that.element).find('tbody').children();
                    for (var i = 0 ;i<trs.length;i++) {
                        $(trs[i]).find('td:first').html(i+1);
                    }
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
        input.attr('placeholder', "New player name");
        input.prop('required', true);
        line2.append(input);
        wrapper.append(line2);

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
