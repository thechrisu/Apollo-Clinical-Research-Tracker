///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../../typings/jquery.d.ts"/>
///<reference path="../columns.ts"/>
///<reference path="../../typings/bootbox.d.ts"/>
/**
 * Single record view typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.5
 */
var SingleView = (function () {
    function SingleView() {
    }
    SingleView.prototype.load = function () {
        var that = this;
        AJAX.get(Util.url('get/record-view/-?id=' + Util.extractId(window.location.toString()), false), function (data) {
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
            columnManager.addToColumn(0, new ColumnRow('Given name', new DataText(data.given_name)));
            columnManager.addToColumn(0, new ColumnRow('Middle name', new DataText(data.middle_name)));
            columnManager.addToColumn(0, new ColumnRow('Last name', new DataText(data.last_name)));
            columnManager.addToColumn(0, new ColumnRow('Email', new DataText(data.email)));
            columnManager.addToColumn(1, new ColumnRow('Phone', new DataText(data.phone)));
            columnManager.addToColumn(1, new ColumnRow('Record name', new DataText(data.record_name)));
            columnManager.addToColumn(1, new ColumnRow('Record start date', new DataDate(data.start_date)));
            columnManager.addToColumn(1, new ColumnRow('Record end date', new DataDate(data.end_date)));
            columnManager.addToColumn(2, new ColumnRow('Address', new DataTextMultiple(data.address)));
            columnManager.render();
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
        var that = this;
        var loader2 = LoaderManager.createLoader($('#additional-panel'));
        LoaderManager.showLoader(loader2, function () {
            var awards = new DataTextMultiple(data.awards);
            var awardsContainer = $('#awards');
            awardsContainer.html('');
            awards.render(awardsContainer);
            var publications = new DataTextMultiple(data.publications);
            var publicationsContainer = $('#publications');
            publicationsContainer.html('');
            publications.render(publicationsContainer);
            that.activityTable = $('#activities');
            if (data.activities == null || data.activities.length < 1)
                that.activityTable.html('<div class="apollo-data-text-multiple"><span class="undefined">None</span></div>');
            else
                that.addActivitiesToTable(data);
            LoaderManager.hideLoader(loader2, function () {
                LoaderManager.destroyLoader(loader2);
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
                var renderable;
                switch (field.type) {
                    case 1:
                        renderable = new DataText(field.value.toString());
                        break;
                    case 2:
                        if (Util.isString(field.value)) {
                            renderable = new DataText(field.value);
                        }
                        else {
                            renderable = new DataTextMultiple(field.value);
                        }
                        break;
                    case 3:
                        renderable = new DataDate(field.value);
                        break;
                    case 4:
                        renderable = new DataLongText(field.value);
                        break;
                }
                columnManager.add(new ColumnRow(field.name, renderable));
            }
            columnManager.render(false);
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    };
    /**
     * With the data of all the activities, it successively creates the rows for each activity
     * @param data
     */
    SingleView.prototype.addActivitiesToTable = function (data) {
        this.activityTable.empty();
        this.activityTable.append($('<div class="table-responsive menu-loader-ready" id="activityTable"><table class="table table-hover small-table table-condensed no-border-top"><tbody id="activity-table-body"></tbody></table></div>'));
        this.activityTable = $('#activity-table-body');
        for (var i = 0; i < data.activities.length; i++) {
            var item = data.activities[i];
            this.addActivityToTable(item);
        }
    };
    /**
     * Successively adds the parameters to one row and adds it to the DOM.
     * @param data
     */
    SingleView.prototype.addActivityToTable = function (data) {
        var row;
        var startD;
        var endD;
        startD = Util.formatShortDate(Util.parseSQLDate(data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(data.end_date));
        row = $('<div class="apollo-data-text-multiple"></div>');
        var name = $('<span></span>');
        name.text(Util.shortify(data.name, 20));
        row.append(name);
        var date = $('<span class="pull-right undefined"></span>');
        date.text(startD + ' - ' + endD);
        row.append(date);
        row.click(function () {
            Util.to('/activity/view/' + data.id);
        });
        row.addClass('selectionItem');
        row.addClass('clickable');
        this.activityTable.append(row);
    };
    SingleView.prototype.setupButtons = function (data) {
        var dropdownCurrent = $('#current-record');
        dropdownCurrent.removeClass('disabled');
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
        addButton.removeClass('disabled');
        var duplicateButton = $('#record-duplicate');
        duplicateButton.removeClass('disabled');
        var editButton = $('#record-edit');
        editButton.removeClass('disabled');
        var hideButton = $('#record-hide');
        hideButton.removeClass('disabled');
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
                            var modal = $('.modal');
                            var name = modal.find('#add-name').val();
                            var startDate = Util.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                            var endDate = Util.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
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
                            var modal = $('.modal');
                            var name = modal.find('#add-name').val();
                            var startDate = Util.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                            var endDate = Util.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
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
                person_id: data.person_id,
                record_name: name,
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
            bootbox.confirm('Are you sure you want to hide this record (belonging to ' + $('<span>' + data.given_name + '</span>').text() + ' ' + $('<span>' + data.last_name + '</span>').text() + ')? The data won\'t be deleted and can be restored later.', function (result) {
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
}());
$(document).ready(function () {
    new SingleView().load();
});
