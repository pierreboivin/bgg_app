$(window).load(function(){
    if (is_page('collection')) {
        // init Isotope
        $('.expansions').hide();
        var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows',
            getSortData: {
                rating: '.rating',
                acquisitiondate: '.acquisitiondate'
            }
        });
        var filters = {};

        $('.grid').isotope( 'on', 'layoutComplete',
            function() {
                $('.nb-games').html($('.element-item:visible').length );
            }
        );

        // Filter mechanics
        $('.filter-mechanics').on('change', function () {
            if ($(this).val()) {
                filters['mechanics'] = '.' + $(this).val();
            } else {
                filters['mechanics'] = '';
            }
            filtersIsotope();
        });
        // Filter playing time
        $('.filter-playingtime').on('click', 'button', function () {
            filterGrid($(this));
            filtersIsotope();
        });
        // Filter players
        $('.filter-players').on('click', 'button', function () {
            var cObj = $(this);
            cObj.parent().find('button').removeClass('active');
            cObj.addClass('active');
            $('.selector-players').val($(this).attr('data-filter-value'));
            filterPlayers();
        });
        $('#players-type-filter').on('change', function () {
            filterPlayers();
        });
        // Sort
        $('.sort-by-button-group').on('click', 'button', function () {
            $(this).parent().find('button').removeClass('active');
            $(this).addClass('active');
            var sortByValue = $(this).attr('data-sort-by');
            var sortDirection = $(this).attr('date-sort-direction') ? 0 : 1;
            $grid.isotope({sortBy: sortByValue, sortAscending: sortDirection});
        });
        // change is-checked class on buttons
        $('.button-group').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.on('click', 'button', function () {
                $buttonGroup.find('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');
            });
        });
        $('#show_expansions').on('change', function () {
            if($(this).is(":checked")) {
                $('.expansions').show();
                $('#collections').addClass('with-expansions');
                $('.grid').isotope( 'reloadItems' ).isotope();
            } else {
                $('.expansions').hide();
                $('#collections').removeClass('with-expansions');
                $('.grid').isotope( 'reloadItems' ).isotope();
            }
        });

        var limitNbExpansions = 3
        $('.expansions').each(function (i) {
            $(this).find('li:gt(' + (limitNbExpansions - 1) + ')').hide();
            if ($(this).find('li').length > limitNbExpansions) {
                $(this).find('.show-more').show();
            } else {
                $(this).find('.show-more').hide();
            }
        });

        filterPlayers = function () {
            var cObj = $('.filter-players');
            var $optionSet = cObj.parent('.option-set');
            var group = $optionSet.attr('data-filter-group');
            var value = $('.selector-players').val();
            if($('#players-type-filter').val() != '') {
                value += '_' + $('#players-type-filter').val();
            }
            filters[group] = value;
            filtersIsotope();
        };

        filterGrid = function (cObj) {
            cObj.parent().find('button').removeClass('active');
            cObj.addClass('active');
            var $optionSet = cObj.parent('.option-set');
            var group = $optionSet.attr('data-filter-group');
            filters[group] = cObj.attr('data-filter-value');
        };
        filtersIsotope = function () {
            var isoFilters = [];
            for (var prop in filters) {
                isoFilters.push(filters[prop])
            }
            var selector = isoFilters.join('');
            $grid.isotope({filter: selector});
        };
    }

});
