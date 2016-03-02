///<reference path="../ajax.ts"/>
$(document).ready(function () {
    var pagination = $('#pagination');
    pagination.pagination({
        items: 0,
        itemsOnPage: 10,
        onPageClick: function (page, event) {
            updateTable(page);
        }
    });
    updateTable(1);
    function updateTable(page) {
        ajaxGet(location.origin + '/api/get/records/?page=' + page, function (data) {
            var table = $('#table-body');
            table.html('');
            pagination.pagination('updateItems', data.count);
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
    $('#table-body').on('click', '.record-tr', function () {
        var that = $(this);
        window.location = location.origin + '/record/view/' + that.data('id') + '/';
    });
});
