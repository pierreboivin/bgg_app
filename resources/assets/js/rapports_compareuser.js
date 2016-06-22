
$(function() {

    if (is_page('rapports_compareuser')) {
        var checkIfCompareDataLoaded = function(compareName) {
            $.getJSON("/compare/check_loading/" + $("#username").val() + "?compare=" + compareName, function(data) {
                if (data == true) {
                    $(location).attr('href', "/rapport/compare/" + $("#username").val() + "?compare=" + compareName)
                } else {
                    checkIfDataLoaded(compareName);
                }
            });
        };

        $('#compareUser').on('submit', function (e) {
            $(this).find('input[type=submit]').button('loading');

            e.preventDefault();

            var compareName = $(this).find('input[name=compare]').val();

            $.getJSON("/compare/loadCompare/" + $("#username").val() + "?compare=" + compareName, function(data) {});

            checkIfCompareDataLoaded(compareName);
        });
    }
});