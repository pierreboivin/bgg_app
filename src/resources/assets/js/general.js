$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    // Buttons table more
    $(".table-more-button").click(function (event) {
        getReplaceTable($(this));
    });

    if($("#username").length) {
        if (getLevelLoad() == 'persistent') {
            var loadBackgroundData = function () {
                $.getJSON("/load/" + $("#username").val() + "?force=1", function (data) {
                });
            };
            var checkIfBackgroundLoaded = function () {
                $.getJSON("/check_loading/" + $("#username").val() + "?force=1", function (data) {
                    $('#background-loading').show();
                    if (data.progress < 100) {
                        setTimeout(
                          function () {
                              checkIfBackgroundLoaded();
                          }, 5000);
                    } else {
                        $('#background-loading').hide();
                    }
                });
            };
            checkIfBackgroundLoaded();
            loadBackgroundData();
        }
    }
    function getReplaceTable(obj) {
        $.ajax({
            url: obj.data('href') + '/' + obj.data('page')
        }).done(function (ajaxReturn) {
            obj.data('page', parseInt(obj.data('page')) + 1);
            $(obj.data('replace')).html(ajaxReturn);
        });
    }

});

function is_page(className) {
    return $("body").hasClass(className);
}
function getLevelLoad() {
    return $("#cacheLevel").val();
}