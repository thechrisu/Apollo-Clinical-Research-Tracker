///<reference path="ajax.ts"/>
///<reference path="scripts.ts"/>
///<reference path="jquery.d.ts"/>
///<reference path="bootbox.d.ts"/>
/**
 * Fields index typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */
var RecordTable = (function () {
    function RecordTable() {
    }
    RecordTable.prototype.load = function () {
        this.table = $('#table-body');
        this.pagination = $('#pagination');
        this.loader = LoaderManager.createLoader($('.table-responsive.loader-ready'));
        this.page = 1;
        this.sort = 1;
        this.search = '';
        this.setup();
        this.updateTable();
    };
    RecordTable.prototype.setup = function () {
        var that = this;
        this.pagination.pagination({
            items: 0,
            itemsOnPage: 10,
            onPageClick: function (page, event) {
                if (event != null) {
                    event.preventDefault();
                }
                that.page = page;
                that.updateTable();
            }
        });
        this.addTabFunctions();
        this.addRecordClick();
        this.addAutoSearch();
    };
    RecordTable.prototype.addTabFunctions = function () {
        var that = this;
        $('#sort-tabs').on('click', '.sort-tab', function () {
            $('.sort-tab').removeClass('active');
            $(this).addClass('active');
            that.sort = $(this).data('sort');
            that.updateTable();
        });
    };
    RecordTable.prototype.addRecordClick = function () {
        this.table.on('click', '.record-tr', function (e) {
            e.preventDefault();
            Util.to('record/view/' + $(this).data('id'));
        });
    };
    RecordTable.prototype.addAutoSearch = function () {
        var that = this;
        var timer = null;
        $('#records-search').keyup(function () {
            clearTimeout(timer);
            that.search = encodeURIComponent($(this).val());
            timer = setTimeout(function () {
                that.updateTable();
            }, AJAX_DELAY);
        });
    };
    RecordTable.prototype.updateTable = function () {
        var that = this;
        LoaderManager.showLoader(that.loader, function () {
            AJAX.get(Util.url('get/records/?page=' + that.page + '&sort=' + that.sort + '&search=' + that.search, false), function (data) {
                if (data.count < (that.page - 1) * 10) {
                    that.pagination.pagination('selectPage', data.count / 10 - data.count % 10);
                    return;
                }
                that.pagination.pagination('updateItems', data.count);
                that.table.html('');
                if (data.count > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        that.renderTr(data.data[i]);
                    }
                }
                else {
                    that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
                }
                LoaderManager.hideLoader(that.loader);
            }, function (message) {
                Util.error('An error has occurred during the loading of the list of records. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    };
    RecordTable.prototype.renderTr = function (data) {
        var tr = $('<tr class="record-tr clickable" data-id="' + data.id + '"></tr>');
        tr.append('<td>' + data.given_name + '</td>');
        tr.append('<td>' + data.last_name + '</td>');
        tr.append('<td>' + data.email + '</td>');
        tr.append('<td>' + data.phone + '</td>');
        this.table.append(tr);
    };
    return RecordTable;
}());
$(document).ready(function () {
    new RecordTable().load();
});
