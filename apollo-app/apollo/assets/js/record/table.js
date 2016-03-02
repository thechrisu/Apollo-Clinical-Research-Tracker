///<reference path="../ajax.ts"/>
$(document).ready(function () {
    var page = 1;
    updateTable();
    function updateTable() {
        ajaxGet(location.origin + '/api/get/records/' + page + '/', function (data) {
            var table = $('#table-body');
            table.html('');
            for (var i = 0; i < data.data.length; i++) {
                var record = data.data[i];
                var tr = $('<tr></tr>');
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
        page = _page;
        updateTable();
    });
});
