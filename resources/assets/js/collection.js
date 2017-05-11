$(window).load(function(){
    if (is_page('collection')) {
        // init Isotope
        $('.expansions').hide();
        var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows',
            getSortData: {
                rating : function(itemElem){
                    return parseInt($(itemElem).find('.rating').text(), 10);
                },
                acquisitiondate: '.acquisitiondate'
            }
        });
        var filters = {};
        var sorting = {value : 'original-order', direction: 'default'};

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
            refreshUrlState();
        });
        // Filter playing time
        $('.filter-playingtime').on('click', 'button', function () {
            filterGrid($(this));
            filtersIsotope();
            refreshUrlState();
        });
        // Filter players
        $('.filter-players').on('click', 'button', function () {
            var cObj = $(this);
            cObj.parent().find('button').removeClass('active');
            cObj.addClass('active');
            $('.selector-players').val($(this).attr('data-filter-value'));
            filterPlayers();
            refreshUrlState();
        });
        $('#filter-players-type').on('change', function () {
            filterPlayers();
            refreshUrlState();
        });
        // Sort
        $('.sort-by-button-group').on('click', 'button', function () {
            $(this).parent().find('button').removeClass('active');
            $(this).addClass('active');
            sorting.value = $(this).attr('data-sort-by');
            sorting.direction = $(this).attr('date-sort-direction');
            filtersIsotope();
            refreshUrlState();
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
            } else {
                $('.expansions').hide();
                $('#collections').removeClass('with-expansions');
            }
            $('.grid').isotope( 'reloadItems' ).isotope();
            refreshUrlState();
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
            var group = cObj.attr('data-filter-group');
            var value = $('.selector-players').val();
            if($('#filter-players-type').val() != '') {
                filters['players-type'] = $('#filter-players-type').val();
            } else {
                filters['players-type'] = '';
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
                if(prop == 'players-type') {
                    isoFilters.push(filters['players'] + filters['players-type'].replace('.', ''))
                } else {
                    isoFilters.push(filters[prop])
                }
            }
            var selector = isoFilters.join('');
            $grid.isotope({filter: selector, sortBy: sorting.value, sortAscending: sorting.direction ? 0 : 1});
        };
        refreshUrlState = function (typeFilter, newFilter) {
            var url = $('input[name="collectionUrl"]').val();
            // Manage filters
            var urlFilterPart = '';
            jQuery.each(filters, function(index, item) {
                if(index && item) {
                    urlFilterPart += '@' + index + '_' + item.replace(".", "");
                }
            });
            if(urlFilterPart) {
                url += '/filter_' + urlFilterPart.substr(1);
            }
            // Manage sorting
            var sortByValue = sorting.value;
            var sortDirection = sorting.direction ? sorting.direction : 'default';
            url += '/sorting_' + sortByValue + "@" + sortDirection;

            // Manage show expansion
            if($('#show_expansions').is(":checked")) {
                url += '/display_expansions'
            }

            history.pushState(null, null, url);
        };

        setDefaultFilterAndSorting = function() {
            var arrayParams = window.location.href.split('/');

            // Manage filters
            var filtersParam = '';
            for(i = 0; i < arrayParams.length; i++) {
                if (arrayParams[i].substring(0, 6) == "filter") {
                    filtersParam = arrayParams[i];
                }
            }
            if(filtersParam.length > 0) {
                filtersParam = filtersParam.substring(7);
                filtersArr = filtersParam.split('@');
                for (i = 0; i < filtersArr.length; i++) {
                    var param = filtersArr[i].split('_');
                    var nameFilter = param[0];
                    var valueFilter = param[1];

                    // Change button selection
                    var container = $('body').find('div[data-filter-group="' + nameFilter + '"]');
                    if(container) {
                        container.find('button').removeClass('active');
                        container.find('button[data-filter-value=".' + valueFilter + '"]').addClass('active');
                    }
                    // Change option selection
                    var container = $('select.filter-' + nameFilter);
                    if(container) {
                        container.val(valueFilter);
                    }

                    // Specific for players selection
                    if(nameFilter == 'players') {
                        $('.selector-players').val('.' + valueFilter);
                    }

                    filters[nameFilter] = '.' + valueFilter;
                }
            }
            // Manage sorting
            var sortingParam = '';
            for(i = 0; i < arrayParams.length; i++) {
                if (arrayParams[i].substring(0, 7) == "sorting") {
                    var param = arrayParams[i].split('_');
                    var sortingProperties = param[1].split('@');
                    var sortByValue = sortingProperties[0];
                    var sortDirection = sortingProperties[1];
                    if(sortDirection == 'default') {
                        sortDirection = '';
                    }
                    sorting.value = sortByValue;
                    sorting.direction = sortDirection;

                    var container = $('body').find('.sort-by-button-group');
                    container.find('button').removeClass('active');
                    container.find('button[data-sort-by="' + sortByValue + '"]').addClass('active');
                }
            }

            // Manage display
            var displayParam = '';
            for(i = 0; i < arrayParams.length; i++) {
                if (arrayParams[i] == 'display_expansions') {
                    $('.expansions').show();
                    $('#collections').addClass('with-expansions');
                    $('#show_expansions').prop('checked', true);
                }
            }
        };
        setDefaultFilterAndSorting();
        filtersIsotope();
    }

});
