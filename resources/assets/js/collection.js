$( function() {
    if(is_page('collection')) {
        // init Isotope
        var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows',
            getSortData: {
                rating: '.rating',
                acquisitiondate: '.acquisitiondate'
            }
        });
        var filters = {};

        // bind filter button click
        $('.filter-playingtime').on('click', 'button', function () {
            filterGrid($(this));
        });
        // bind filter button click
        $('.filter-players').on('click', 'button', function () {
            filterGrid($(this));
        });
        // bind sort by button click
        $('.sort-by-button-group').on('click', 'button', function() {
            $(this).parent().find('button').removeClass('active');
            $(this).addClass('active');
            var sortByValue = $(this).attr('data-sort-by');
            var sortDirection = $(this).attr('date-sort-direction') ? 0 : 1;
            $grid.isotope({ sortBy: sortByValue, sortAscending: sortDirection });
        });
        // change is-checked class on buttons
        $('.button-group').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.on('click', 'button', function () {
                $buttonGroup.find('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');
            });
        });

        filterGrid = function(cObj) {
            cObj.parent().find('button').removeClass('active');
            cObj.addClass('active');
            var $optionSet = cObj.parent('.option-set');
            var group = $optionSet.attr('data-filter-group');
            filters[ group ] = cObj.attr('data-filter-value');
            // convert object into array
            var isoFilters = [];
            for ( var prop in filters ) {
                isoFilters.push( filters[ prop ] )
            }
            var selector = isoFilters.join('');
            $grid.isotope({filter: selector});
        }
    }

});
