///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
///<reference path="../columns.ts"/>
///<reference path="../bootbox.d.ts"/>
///<reference path="../inputs.ts"/>
/**
 * Single record view typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.5
 */

interface EssentialData {
    given_name:string,
    middle_name:string,
    last_name:string,
    email:string,
    address:string[],
    phone:string,
    start_date:string,
    end_date:string,
    person_id:number,
    record_id:number,
    record_name:string,
    record_ids:number[],
    record_names:string[]

}
interface FieldData {
    name:string,
    type:number,
    value:string|string[]
}
interface RecordData {
    error:Error,
    essential:EssentialData,
    data:FieldData[]
}

class SingleView {

    private id:number;

    public load(id:number) {

        // var test = $('#test');
        // var type = 7;
        // var input;
        // switch (type) {
        //     case 1:
        //         input = new InputText(1, function(id:number, value:string) {
        //             alert('Value is: ' + value);
        //         }, { placeholder: 'Test' }, 'Some Value');
        //         break;
        //     case 2:
        //         input = new InputDropdown(2, function(id:number, value:string|number|number[]) {
        //             alert(value);
        //         }, ['First option', 'Second option'], 1, true, 'test');
        //         break;
        //     case 3:
        //         input = new InputDropdown(2, function(id:number, value:string|number|number[]) {
        //             alert(value);
        //         }, ['First option', 'Second option'], 1, false, null, true);
        //         break;
        //     case 4:
        //         input = new InputTextMultiple(4, function (id:number, value:string[]) {
        //             console.log(value);
        //         }, {
        //             placeholder: 'Tester'
        //         }, ['First', 'Second']);
        //         break;
        //     case 5:
        //         input = new InputLongText(5, function(id:number, value:string) {
        //             console.log(value)
        //         }, { placeholder: 'Test' }, 'Some Value');
        //         break;
        //     case 6:
        //         input = new InputDate(6, function(id:number, value:string) {
        //             console.log(value)
        //         }, { placeholder: 'Test' }, '22/03/2014');
        //         break;
        //     case 7:
        //         input = new InputNumber(1, function(id:number, value:string) {
        //             alert('Value is: ' + value);
        //         }, { placeholder: 'Test' }, 4);
        //         break;
        // }
        // input.render(test);

        this.id = id;
        var that = this;

        AJAX.get(Util.url('get/record-edit/?id=' + this.id, false), function (data:RecordData) {
            var breadcrumbs = $('#nav-breadcrumbs');
            breadcrumbs.find('li:nth-child(3)').text(data.essential.given_name + ' ' + data.essential.last_name);
            breadcrumbs.find('li:nth-child(4)').text('Record #' + data.essential.record_id + ': ' + data.essential.record_name);
            that.parseEssentials(data.essential);
            that.parseFields(data.data);
            that.setupButtons(data.essential);
        }, function (message:string) {
            Util.error('An error has occurred during the loading of single record data. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }
    
    private parseEssentials(data:EssentialData) {
        var loader = LoaderManager.createLoader($('#essential-panel'));
        LoaderManager.showLoader(loader, function () {
            var columnManager = new ColumnManager('#essential');
            //TODO Tim: Refactor this
            function wrap(value:string|string[], type:number = 2) {
                if (type == 3) {
                    return '<div class="input-group date" data-provide="datepicker">' +
                        '<input id="add-end-date" type="text" value="' + value + '" class="form-control input-sm input-block-level"' +
                        '><span class="input-group-addon" style="padding: 0 18px !important; font-size: 0.8em !important;"><i class="glyphicon glyphicon-th"></i></span>' +
                        '</div>';
                } else {
                    return '<input type="text" class="form-control input-sm input-block-level" value="' + value + '">';
                }
            }
    
            columnManager.addToColumn(0, new ColumnRowStatic('Given name', wrap(data.given_name)));
            columnManager.addToColumn(0, new ColumnRowStatic('Middle name', wrap(data.middle_name)));
            columnManager.addToColumn(0, new ColumnRowStatic('Last name', wrap(data.last_name)));
            columnManager.addToColumn(0, new ColumnRowStatic('Email', wrap(data.email)));
            columnManager.addToColumn(1, new ColumnRowStatic('Phone', wrap(data.phone)));
            columnManager.addToColumn(1, new ColumnRowStatic('Record name', wrap(data.record_name)));
            columnManager.addToColumn(1, new ColumnRowStatic('Record start date', wrap(data.start_date, 3)));
            columnManager.addToColumn(1, new ColumnRowStatic('Record end date', wrap(data.end_date, 3)));
            columnManager.addToColumn(2, new ColumnRowStatic('Address', wrap(data.address)));
            columnManager.render();
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    }

    private parseFields(data:FieldData[]) {
        var loader = LoaderManager.createLoader($('#fields'));
        LoaderManager.showLoader(loader, function () {
            var count = data.length;
            var columnManager = new ColumnManager('#fields', 3, count);
            for (var i = 0; i < count; i++) {
                var field = data[i];
                var value = field.value;
                if (field.type == 3) {
                    value = Util.formatDate(Util.parseSQLDate(<string> value));
                }
                columnManager.add(new ColumnRowStatic(field.name, value));
            }
            columnManager.render(false);
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    }

    private setupButtons(data:EssentialData) {
        var dropdownCurrent = $('#current-record');
        var dropdownOther = $('#other-records');
        dropdownCurrent.html(data.record_name + ' <span class="caret"></span>');
        if (data.record_ids.length > 0) {
            for (var i = 0; i < data.record_ids.length; i++) {
                dropdownOther.append('<li><a href="' + Util.url('record/view/' + data.record_ids[i]) + '">' + data.record_names[i] + '</a></li>');
            }
        } else {
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
                                var modal = $('.modal');
                                var name = modal.find('#add-name').val();
                                var startDate = Util.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                                var endDate = Util.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
                                newRecord(name, startDate, endDate);
                            }
                        }
                    }
                }
            );
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
                }
            );
        });

        function newRecord(name:string, startDate:string, endDate:string, id:number = 0) {
            AJAX.post(Util.url('post/record'), {
                action: 'add',
                person_id: data.person_id,
                record_name: name,
                start_date: startDate,
                end_date: endDate,
                id: id
            }, function (response:any) {
                Util.to('record/edit/' + response.record_id);
            }, function (message:string) {
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
                    }, function (data:any) {
                        Util.to('record');
                    }, function (message:string) {
                        Util.error('An error has occurred during hiding of the record. Error message: ' + message);
                    });
                }
            });
        });
    }

}

$(document).ready(function () {
    var single = new SingleView();
    var id = Util.extractId(window.location.toString());
    single.load(id);
});