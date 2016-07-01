///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../../typings/jquery.d.ts"/>
///<reference path="../../typings/bootbox.d.ts"/>
///<reference path="../columns.ts"/>
///<reference path="../apollopagination.ts"/>
/**
 * Records index typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.8
 */
var RecordTable = (function () {
    function RecordTable() {
    }
    RecordTable.prototype.load = function () {
        this.table = $('#table-body');
        this.loader = LoaderManager.createLoader($('.table-responsive.loader-ready'));
        this.page = 1;
        this.sort = 1;
        this.search = '';
        this.setup();
        this.updateTable();
    };
    RecordTable.prototype.setup = function () {
        var that = this;
        var paginationWrapper = $('#pagination');
        this.pagination = new ApolloPagination(paginationWrapper, {
            items: 0,
            itemsOnPage: 10,
            onPageClick: function (page, event) {
                if (event != null) {
                    event.preventDefault();
                }
                that.page = page;
                that.updateTable();
            }
        }, that.page);
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
            WebUtil.to('record/view/' + $(this).data('id'));
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
            AJAX.get(StringUtil.url('get/records/?page=' + that.page + '&sort=' + that.sort + '&search=' + that.search, false), function (data) {
                var tooFewElements = that.pagination.updateNumPagesSmart(data.count);
                if (tooFewElements)
                    return;
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
                WebUtil.error('An error has occurred during the loading of the list of records. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    };
    RecordTable.prototype.renderTr = function (data) {
        var tr = $('<tr class="record-tr clickable" data-id="' + data.id + '"></tr>');
        [data.given_name, data.last_name, data.email, data.phone].forEach(function (string) {
            var td = $('<td></td>');
            var field = new DataText(StringUtil.shortify(string, 50));
            field.renderPlain(td);
            tr.append(td);
        });
        this.table.append(tr);
    };
    return RecordTable;
}());
$(document).ready(function () {
    new RecordTable().load();
    $('#add-record').click(function (e) {
        e.preventDefault();
        bootbox.dialog({
            title: 'Creating a new person and a record',
            message: $('#add-modal').html(),
            buttons: {
                main: {
                    label: "Cancel",
                    className: "btn-primary",
                    callback: function () {
                    }
                },
                success: {
                    label: "Add",
                    className: "btn-success",
                    callback: function () {
                        var modal = $('.modal');
                        var givenName = modal.find('#add-given-name').val();
                        var middleName = modal.find('#add-middle-name').val();
                        var lastName = modal.find('#add-last-name').val();
                        var recordName = modal.find('#add-record-name').val();
                        var startDate = DateUtil.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                        var endDate = DateUtil.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
                        AJAX.post(StringUtil.url('post/record'), {
                            action: 'create',
                            given_name: givenName,
                            middle_name: middleName,
                            last_name: lastName,
                            record_name: recordName,
                            start_date: startDate,
                            end_date: endDate
                        }, function (response) {
                            WebUtil.to('record/edit/' + response.record_id);
                        }, function (message) {
                            WebUtil.error('An error has occurred during the process of creation of a new record for a person. Error message: ' + message);
                        });
                    }
                }
            }
        });
    });
});
