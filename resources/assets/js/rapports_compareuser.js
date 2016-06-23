
$(function() {

    if (is_page('rapports_compareuser')) {
        var manageSubmit = function(formObj, compareName) {
            if(compareName == "" || compareName == 0) {
                $('#messages').html('<div class="alert alert-danger">Vous devez fournir un nom d\'utilisateur valide</div>');
            } else {
                $('#messages').html('');
                formObj.find('input[type=submit]').button('loading');

                $.getJSON("/compare/loadCompare/" + $("#username").val() + "?compare=" + compareName, function (data) {});

                checkIfCompareDataLoaded(compareName);
            }
        };
        var checkIfCompareDataLoaded = function(compareName) {
            $.getJSON("/compare/check_loading/" + $("#username").val() + "?compare=" + compareName, function(data) {
                if (data == true) {
                    $(location).attr('href', "/rapport/compare/" + $("#username").val() + "?compare=" + compareName)
                } else {
                    setTimeout(
                        function()
                        {
                            checkIfCompareDataLoaded(compareName);
                        }, 1000);
                }
            });
        };

        $('#compareUser').on('submit', function (e) {
            e.preventDefault();
            var compareName = $(this).find('input[name=compare_user]').val();
            manageSubmit($(this), compareName);

        });
        $('#compareBuddy').on('submit', function (e) {
            e.preventDefault();
            var compareName = $(this).find('select[name=compare_buddy]').val();
            manageSubmit($(this), compareName);
        });
    }
});