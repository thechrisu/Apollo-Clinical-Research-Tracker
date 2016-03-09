///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
///<reference path="../columns.ts"/>
///<reference path="../bootbox.d.ts"/>
/**
 * Single record view typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.4
 */
var SingleView = (function () {
    function SingleView() {
    }
    SingleView.prototype.load = function () {
        var that = this;
        AJAX.get(Util.url('get/record'), function (data) {
            var breadcrumbs = $('#nav-breadcrumbs');
            breadcrumbs.find('li:nth-child(3)').text(data.essential.given_name + ' ' + data.essential.last_name);
            breadcrumbs.find('li:nth-child(4)').text('Record #' + data.essential.record_id + ': ' + data.essential.record_name);
            that.parseEssentials(data.essential);
            that.parseFields(data.data);
            that.setupButtons(data.essential);
        }, function (message) {
            Util.error('An error has occurred during the loading of single record data. Please reload the page or contact the administrator. Error message: ' + message);
        });
    };
    SingleView.prototype.parseEssentials = function (data) {
        var loader = LoaderManager.createLoader($('#essential-panel'));
        LoaderManager.showLoader(loader, function () {
            var columnManager = new ColumnManager('#essential');
            columnManager.addToColumn(0, new ColumnRow('Given name', data.given_name));
            columnManager.addToColumn(0, new ColumnRow('Middle name', data.middle_name));
            columnManager.addToColumn(0, new ColumnRow('Last name', data.last_name));
            columnManager.addToColumn(1, new ColumnRow('Email', data.email));
            columnManager.addToColumn(1, new ColumnRow('Phone', data.phone));
            columnManager.addToColumn(2, new ColumnRow('Address', data.address));
            columnManager.render();
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    };
    SingleView.prototype.parseFields = function (data) {
        var loader = LoaderManager.createLoader($('#fields'));
        LoaderManager.showLoader(loader, function () {
            var count = data.length;
            var columnManager = new ColumnManager('#fields', 3, count);
            for (var i = 0; i < count; i++) {
                var field = data[i];
                var value = field.value;
                if (field.type == 3) {
                    value = Util.formatDate(Util.parseSQLDate(value));
                }
                columnManager.add(new ColumnRow(field.name, value));
            }
            columnManager.render(false);
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    };
    SingleView.prototype.setupButtons = function (data) {
        var dropdownCurrent = $('#current-record');
        var dropdownOther = $('#other-records');
        dropdownCurrent.html(data.record_name + ' <span class="caret"></span>');
        if (data.record_ids.length > 0) {
            for (var i = 0; i < data.record_ids.length; i++) {
                dropdownOther.append('<li><a href="' + Util.url('record/view/' + data.record_ids[i]) + '">' + data.record_names[i] + '</a></li>');
            }
        }
        else {
            dropdownOther.append('<li class="dropdown-header">Nothing to display . . .</li>');
        }
        var addButton = $('#record-add');
        var duplicateButton = $('#record-duplicate');
        var editButton = $('#record-edit');
        var hideButton = $('#record-hide');
        addButton.click(function (e) {
            e.preventDefault();
            bootbox.dialog({
                title: 'Adding a new record for ' + data.given_name + ' ' + data.last_name,
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
                            var name = $('.modal').find('#add-name').val();
                            var startDate = $('.modal').find('#add-start-date').val();
                            var endDate = $('.modal').find('#add-end-date').val();
                            newRecord(name, startDate, endDate);
                        }
                    }
                }
            });
        });
        duplicateButton.click(function (e) {
            e.preventDefault();
            bootbox.dialog({
                title: 'Adding a new record for ' + data.given_name + ' ' + data.last_name,
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
                            var name = $('.modal').find('#add-name').val();
                            var startDate = $('.modal').find('#add-start-date').val();
                            var endDate = $('.modal').find('#add-end-date').val();
                            newRecord(name, startDate, endDate, data.record_id);
                        }
                    }
                }
            });
        });
        function newRecord(name, startDate, endDate, id) {
            if (id === void 0) { id = 0; }
            AJAX.post(Util.url('post/record'), {
                action: 'add',
                name: name,
                start_date: startDate,
                end_date: endDate,
                id: id
            }, function (response) {
                Util.to('record/edit/' + response.record_id);
            }, function (message) {
                Util.error('An error has occurred during the process of creation of the record. Error message: ' + message);
            });
        }
        editButton.attr('href', Util.url('record/edit/' + data.record_id));
        hideButton.click(function (e) {
            e.preventDefault();
            bootbox.confirm('Are you sure you want to hide this record (belonging to ' + data.given_name + ' ' + data.last_name + ')? The data won\'t be deleted and can be restored later.', function (result) {
                if (result) {
                    AJAX.post(Util.url('post/record'), {
                        action: 'hide',
                        id: data.record_id
                    }, function (data) {
                        Util.to('record');
                    }, function (message) {
                        Util.error('An error has occurred during hiding of the record. Error message: ' + message);
                    });
                }
            });
        });
    };
    return SingleView;
})();
$(document).ready(function () {
    new SingleView().load();
});
