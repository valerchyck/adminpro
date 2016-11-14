function goToPage(e, url) {
    var separator = url.indexOf('?') > -1 ? '&' : '?';
    location = url + separator + 'page=' + $(e).prev().val();
}

$(document).ready(function() {
    $('[name="page-input"]').on('input', function(e) {
        var v = $(this).val().replace(/[^1-9]+/i, '');
        $(this).val(v);
    });
});
