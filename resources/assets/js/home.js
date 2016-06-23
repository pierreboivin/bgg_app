$( function() {
    if(is_page('home')) {
        var loadData = function() {
            $.getJSON("/load/" + $("#username").val(), function(data) {});
        };
        var checkIfDataLoaded = function() {
            $.getJSON("/check_loading/" + $("#username").val(), function(data) {
                $('#progression .progress-bar').html(data.message);
                $('#progression .progress-bar').width(data.progress + '%');
                if (data.progress < 100) {
                    setTimeout(
                        function()
                        {
                            checkIfDataLoaded();
                        }, 1000);
                } else {
                    dataCompleted();
                }
            });
        };
        var desactivateNavigation = function() {
            $('a.desactivate-if-not-loaded').bind("click", function(e) {
                e.preventDefault();
            });
        };
        var dataCompleted = function() {
            $('#progression').hide();
            $('#progression-message-warning').hide();
            $('#progression-message-success').show();
            $('a.desactivate-if-not-loaded').unbind("click");
        };
        $('#progression-message-success').hide();
        desactivateNavigation();
        loadData();
        checkIfDataLoaded();
    }
});