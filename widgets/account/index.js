function setStatus(e) {
    $.get('/agent/set-work-status', {value: ~~$(e).is(':checked')});
}
