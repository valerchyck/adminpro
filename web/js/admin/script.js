$(document).ready(function() {
    $.getJSON('control/tables', function(response) {
        var target = $('[name="query"]')[0];
        var tables = [];

        $(response).each(function () {
            tables.push(this.name);
        });

        var sql = CodeMirror.fromTextArea(target, {
            mode: 'text/x-sql',
            indentWithTabs: true,
            smartIndent: true,
            lineNumbers: true,
            matchBrackets : true,
            autofocus: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete"
            },
            hintOptions: {
                tables: tables
            }
        });

        sql.on('keydown', function(e, value) {
            sql.getDoc().cm.focus();
            sql.getDoc().cm.save();
            if (value.keyCode == 13 && value.ctrlKey) {
                $('#sql-form').submit();
            }
        });

        $(this).on('keydown', function(e) {
            if (e.keyCode == 27) {
                $('body').focus();
            }
        });
        sql.getDoc().cm.execCommand('goDocEnd');
    });
});
