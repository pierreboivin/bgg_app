$( function() {
    if(is_page('home')) {
        $('#btnUserLogin').click(function () {
            $('#btnUserLogin').button('loading');
        });
        $('#btnGuestLogin').click(function () {
            $('#btnGuestLogin').button('loading');
        });

        var dataCompleted = function (regenerate) {
            $('#progression').hide();
            $('#progression-message-warning').hide();
            $('#progression-message-success').show();
            if (regenerate) {
                $('#progression-message-success').append('<br>Certaines statistiques expirés doivent être rechargées.');
            }
            $('a.desactivate-if-not-loaded').unbind("click");
        };
        if(getLevelLoad() == 'none') {
            var loadData = function () {
                $.getJSON("/load/" + $("#username").val(), function (data) {
                });
            };
            var checkIfDataLoaded = function () {
                $.getJSON("/check_loading/" + $("#username").val(), function (data) {
                    $('#progression .progress-bar').html(data.message);
                    $('#progression .progress-bar').width(data.progress + '%');
                    if (data.progress < 100) {
                        setTimeout(
                          function () {
                              checkIfDataLoaded();
                          }, 1000);
                    } else {
                        dataCompleted(data.regenerate);
                    }
                });
            };
            loadData();
            checkIfDataLoaded();

            $('#progression-message-success').hide();
            var desactivateNavigation = function() {
                $('a.desactivate-if-not-loaded').bind("click", function(e) {
                    e.preventDefault();
                });
            };
            desactivateNavigation();
        } else if(getLevelLoad() == 'persistent') {
            dataCompleted(true);
        } else {
            dataCompleted(false);
        }
    }
});