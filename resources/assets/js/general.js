$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function is_page(className) {
    return $("body").hasClass(className);
}
