$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#btnUserLogin').click(function () {
        $('#btnUserLogin').button('loading');
    });
    $('#btnGuestLogin').click(function () {
        $('#btnGuestLogin').button('loading');
    });
});

function is_page(className) {
    return $("body").hasClass(className);
}
