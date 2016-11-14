$(document).ready(function() {
    $('#main-menu').navBar();

    $('.target').attr('target', '_blank');
    // set last records
    $('.grid-view tbody tr').each(function() {
        $(this).on('click', function(e) {
            var
                target = this,
                type = $(this).attr('last-record-type'),
                value = $(this).attr('last-record-value');
            
            $.ajax({
                url: '/item/last-record',
                data: {
                    type: type,
                    value: value
                },
                success: function(response) {
                    $('.grid-view tbody tr').removeClass('last-record');
                    $(target).addClass('last-record');
                }
            });
        });
    });

	$('.popover-panel').popover();

    if ($('textarea').length > 0)
        $('textarea').autosize();

    $('.info.grid-view tbody tr').on('click', function(e) {
        if (!$(e.target).is('input, span')) {
            $.get('/item/details?id=' + $(this).attr('data-key'), function (response) {
                $('#detail .modal-body').html(response);
                $('#detail .modal-body a').attr('target', '_blank');
                $('#detail').modal('show');
            });
        }
    });

    $('#finish').on('click', function() {
        if (!checkSelectedItems())
            return false;

        var can = true,
            items = $('[name="selection[]"]:checked');

        $(items).each(function() {
            if ($.isEmptyObject($(this).parents('tr').attr('isFinish')) || $(this).parents('tr').attr('isFinish') != 0) {
                alert('Невозможно завершить данные задачи');
                can = false;
                return false;
            }
        });
        if (!can)
            return false;

        $.get('/task/finish', {tasks: getSelectedItems}, function(response) {
            $.pjax.reload('#hot-list-grid-pjax');
        });
    });

    $('.grid-view .delete').on('click', function() {
        if ($(this).parents('tr').attr('isFinish') == 0) {
            alert('Невозможно завершить данные задачи');
            return false;
        }

        if (!confirm('Вы уверены, что хотети удалить этот элемент?'))
            return false;

    });

    $('#categories').on('change', function() {
        location = url([$(this).attr('url')], ['idCategory=' + $(this).val(), 'idAgent=' + $('[name="agent"]').val()]);
    });

    // statistic events
    $('.statistic-table a').on('click', function() {
        $.get('/admin/get-statistic', {idCategory: $(this).attr('category'), isFinish: $(this).attr('isFinish'), isHot: $(this).attr('isHot'), date: $(this).attr('date')}, function(response) {
            $('#details-grid-content').html(response);
            $('#details').modal('show');
        });
    });

    $('.data-filter').on('change', function() {
        location = $(this).find(':selected').attr('url');
    });
});

function addTask(e) {
    if (!checkSelectedItems())
        return false;

    if ($('[name="agent"]').val().length < 1) {
        alert('Выберите агента');
        return false;
    }

    $.get('/task/add', {idAgent: $('[name="agent"]').val(), taskIds: getSelectedItems}, function (response) {
        $.pjax.reload('#hot-list-grid-pjax');
    });
}

function clientOrders(id) {
    $.get('/admin/client-orders', {id: id}, function(response) {
        $('#orders .modal-body').html(response);
        $('#orders').modal('show');
    });
}

function url(params, get) {
    return params.join('/') + '?' + get.join('&');
}

map = null;
infowindow = null;
mapKeywords = [];

function initialize() {
    var pyrmont = new google.maps.LatLng(-33.8665433, 151.1956316);

    map = new google.maps.Map(document.getElementById('map'), {
        center: pyrmont,
        zoom: 15
    });

    var request = {
        query: mapKeywords.join(',')
    };
    infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    service.textSearch(request, callback);
}

function callback(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    }
}

function createMarker(place) {
    var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
    });
    map.setCenter(place.geometry.location);

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(place.name);
        infowindow.open(map, this);
    });
}

function refreshSelect2(selector, data, type) {
    var name = type == 'streets' ? 'улицу' : 'метро';
    var html = '<option value="">Выберите ' + name + '</option>';

    $(data).each(function (key, value) {
        html += '<option value="' + value + '">' + value + '</option>';
    });

    $(selector).html(html);
    $(selector).select2({
        theme:    "krajee",
        width:    "100%",
        language: "ru-RU"
    });
}

function loadStreets(e, id) {
    $.ajax({
        type: 'get',
        url: '/client/data-list',
        data: {
            name: $(e).val(),
            type: 'street'
        },
        success: function(response) {
            refreshSelect2($('#'+id).find('select.streets'), response, 'streets');
        }
    });
}

function loadMetro(e, id) {
    $.ajax({
        type: 'get',
        url: '/client/data-list',
        data: {
            name: $(e).val(),
            type: 'metro'
        },
        success: function(response) {
            refreshSelect2($('#'+id).find('select.metro'), response, 'metro');
        }
    });
}

function edit(id) {
    var params = '';
    if (id != undefined)
        params = '?id=' + id;

    $.ajax({
        method: 'post',
        url: '/notice/edit-template/' + params,
        success: function (response) {
            $('#modal-template .modal-body').html(response);
            $('#modal-template').modal('show');
        }
    });
}

function showDetails(e, dopInfo) {
    if (dopInfo == 0)
        return false;

    if (!$(event.target).is('input, span')) {
        $.get('/item/details?id=' + $(e).data('key'), function (response) {
            $('#detail .modal-body').html(response);
            $('#detail .modal-body a').attr('target', '_blank');
            $('#detail').modal('show');
        });
    }
}

function setEdited(id) {
    $.get('/item/set-edited/', {id: id}, function () {
        location.reload();
    });
}

function restoreCustom(id) {
    $.get('/custom/select-agent', {id: id}, function (response) {
        $('#restore-modal .modal-body').html(response);
        $('#restore-modal').modal('show');
    });
}

$.fn.navBar = function () {
    $(this).find('li').on('mouseover', function () {
        if ($(this).children('ul').length > 0)
            $(this).addClass('open');
    });

    $(this).find('li').on('mouseleave', function () {
        $(this).removeClass('open');
    });
};
