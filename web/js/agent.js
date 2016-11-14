agent = {
    access: function (e) {
        $.get('/admin/agent-categories', {id: $(e).attr('agent-id')}, function(response) {
            $('#agent-categories .modal-body').html(response);
            $('#agent-categories').modal('show');
        });
    },
    tasks: function (e) {
        $.post('/tasks', {id: $(e).attr('agent-id')}, function(response) {
            $('#agent-tasks .modal-body').html(response);
            $('#agent-tasks').modal('show');
        });
    },
    streets: function (e, id) {
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
    },
    metro: function (e, id) {
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
};
