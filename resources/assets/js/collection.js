$( function() {
    if(is_page('collection')) {
        // init Isotope
        var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows',
            getSortData: {
                rating: '.rating'
            }
        });
        // bind filter button click
        $('.filter-playingtime').on('click', 'button', function () {
            $(this).parent().find('button').removeClass('active');
            $(this).addClass('active');
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({filter: filterValue});
        });
        // bind sort by button click
        $('.sort-by-button-group').on( 'click', 'button', function() {
            $(this).parent().find('button').removeClass('active');
            $(this).addClass('active');
            var sortByValue = $(this).attr('data-sort-by');
            $grid.isotope({ sortBy: sortByValue });
        });
        // change is-checked class on buttons
        $('.button-group').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.on('click', 'button', function () {
                $buttonGroup.find('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');
            });
        });
    }

});
