$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    if(getLevelLoad() == 'persistent') {
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
                      }, 1000);
                } else {
                    $('#background-loading').hide();
                }
            });
        };
        checkIfBackgroundLoaded();
        loadBackgroundData();
    }

});

function is_page(className) {
    return $("body").hasClass(className);
}
function getLevelLoad() {
    return $("#cacheLevel").val();
}