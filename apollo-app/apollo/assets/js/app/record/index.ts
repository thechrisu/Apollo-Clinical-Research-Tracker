///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.4
 */

$(document).ready(function () {
    var pagination = $('#pagination');
    var loader = $('#loader');
    var page = 1;
    var sort = 1;
    pagination.pagination({
        items: 0,
        itemsOnPage: 10,
        onPageClick: function (_page, event) {
            event.preventDefault();
            page = _page;
            updateTable();
        }
    });
    updateTable();
    function updateTable() {
        loader.fadeIn(200);
        AJAX.get(url('get/records/?page=' + page + '&sort=' + sort, false), function (data) {
            var table = $('#table-body');
            table.html('');
            pagination.pagination('updateItems', data.count);
            if(data.count > 0) {
                for (var i = 0; i < data.data.length; i++) {
                    var record = data.data[i];
                    var tr = $('<tr style="cursor: pointer" class="record-tr" data-id="' + record.id + '"></tr>');
                    tr.append('<td>' + record.given_name + '</td>');
                    tr.append('<td>' + record.last_name + '</td>');
                    tr.append('<td>' + record.email + '</td>');
                    tr.append('<td>' + record.phone + '</td>');
                    table.append(tr);
                }
            } else {
                var tr = $('<tr></tr>');
                tr.append('<td colspan="4" class="text-center"><b>Nothing to display...</b></td>');
                table.append(tr);
            }
            loader.fadeOut(200);
        }, function (message:string) {
            error('An error has occurred during the loading of the list of records. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }
    $('#sort-tabs').on('click', '.sort-tab', function() {
        var that = $(this);
        $('.sort-tab').removeClass('active');
        that.addClass('active');
        sort = that.data('sort');
        updateTable();
    });
    $('#table-body').on('click', '.record-tr', function (e) {
        e.preventDefault();
        var that = $(this);
        location.href = url('record/view/' + that.data('id'));
    });
});