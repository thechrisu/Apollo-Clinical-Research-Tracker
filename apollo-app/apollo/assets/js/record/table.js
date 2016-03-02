///<reference path="../ajax.ts"/>
$(document).ready(function () {
    updateTable(1);
    function updateTable(page) {
        ajaxGet(location.origin + '/api/get/records/?page=' + page, function (data) {
            var table = $('#table-body');
            table.html('');
            for (var i = 0; i < data.data.length; i++) {
                var record = data.data[i];
                var tr = $('<tr style="cursor: pointer" class="record-tr" data-id="' + record.id + '"></tr>');
                tr.append('<td>' + record.given_name + '</td>');
                tr.append('<td>' + record.last_name + '</td>');
                tr.append('<td>' + record.email + '</td>');
                tr.append('<td>' + record.phone + '</td>');
                table.append(tr);
            }
        }, function (message) {
            alert('Error! ' + message);
        });
    }
    $('#pagination').on('click', 'a', function () {
        var that = $(this);
        var _page = parseInt(that.text());
        updateTable(_page);
    });
    $('#table-body').on('click', '.record-tr', function () {
        var that = $(this);
        window.location = location.origin + '/record/view/' + that.data('id') + '/';
    });
});
