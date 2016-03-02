///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.2
 */

$(document).ready(function () {
    var pagination = $('#pagination');
    pagination.pagination({
        items: 0,
        itemsOnPage: 10,
        onPageClick: function (page, event) {
            event.preventDefault();
            updateTable(page);
        }
    });
    updateTable(1);
    function updateTable(page:number) {
        AJAX.get(url('api/get/records/?page=' + page, false), function (data) {
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
        }, function (message:string) {
            error('An error has occurred during the loading of the list of records. Please reload the page or contact the administrator.');
        });
    }

    $('#table-body').on('click', '.record-tr', function () {
        var that = $(this);
        location.href = url('record/view/' + that.data('id'));
    });
});