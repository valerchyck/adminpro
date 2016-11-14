function move(e) {
    if (!checkSelectedItems())
        return false;

    send('/admin/move', function () {
        $.pjax.reload('#hot-list-grid-pjax');
    });
}

function removeItems(e) {
    if (!checkSelectedItems())
        return false;

    if (confirm('Вы уверены что хотите удалить данные записи?')) {
        send('/item/hide', function (response) {
            alertify.success('Удалено записей: ' + response);
            $.pjax.reload('#hot-list-grid-pjax');
        });
    }
}

function print(e) {
    if (!checkSelectedItems())
        return false;

    send('/site/print', function(response) {
        $(response).find('#for-print').printThis();
    });
}

function setPageSize(e) {
    $.post('/item/page-size', {size: $(e).val()}, function () {
        $.pjax.reload('#hot-list-grid-pjax');
    });
}

function setCategory(e, action) {
    $.get('/item/set-category', {id: $(e).val(), action: action}, function () {
        $.pjax.reload('#hot-list-grid-pjax');
    });
}

function setAgent(e) {
    $.get('/admin/set-agent', {id: $(e).val()}, function () {
        $.pjax.reload('#hot-list-grid-pjax');
    });
}

function send(url, callback) {
    $.ajax({
        url: url,
        type: 'post',
        data: {selection: getSelectedItems()},
        success: callback,
        error: function () {
            alertify.error('Ошибка');
        }
    });
}

function getSelectedItems() {
    var data = [];

    $('[name="selection[]"]:checked').each(function () {
        data.push($(this).val());
    });

    return data;
}

function checkSelectedItems() {
    if ($('[name="selection[]"]:checked').length < 1) {
        alert('Выберите записи');
        return false;
    }

    return true;
}

function removeTemplate(id) {
    if (confirm('Удалить шаблон?')) {
        $.ajax({
            method: 'post',
            url: '/notice/remove-template/?id=' + id
        });
    }
}

function setMacros(e) {
    var cursorPosition = $('#template-text').prop('selectionStart'),
        text           = $('#template-text').val(),
        value          = $(e).val();

    var start = text.substr(0, cursorPosition);
    var end   = text.substr(cursorPosition);

    $('#template-text').val(start + '[' + value + ']' + end);
}

function sendForm(e) {
    if (!checkSelectedItems())
        return false;

    send('/notice/send-form', function (response) {
        $('#send-template .modal-body').html(response);
        $('#send-template').modal('show');
    });
}

function sendNotices(e) {
    var users = $('#send-users .ui-selected');
    if (users.length < 1) {
        alert('Выберите юзера');
        return false;
    }

    var userIds  = [],
        template = $('#templates-select').val();

    $(users).each(function () {
        userIds.push($(this).data('id'));
    });

    $.ajax({
        url: '/notice/send',
        type: 'post',
        data: {users: userIds, template: template, records: records},
        success: function (response) {
            if (response == true) {
                $('#send-template').modal('hide');
                alertify.success('Сообщения успешно отправлены');
            }
            else {
                alertify.success('Произошка ошибка при отправке');
            }
        },
        error: function() {
            alertify.error('Ошибка');
        }
    });
}
