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
 * @version 0.1.0
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
interface FieldEditData {
    id:number,
    name:string,
    type:number,
    has_default:boolean,
    allow_other:boolean,
    is_multiple:boolean,
    defaults:string[],
    value:number|number[]|string|string[]
}
interface RecordData {
    error:Error,
    essential:EssentialData,
    data:FieldEditData[]
}

class SingleView {

    private id:number;

    public load(id:number) {

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
        var that = this;
        var loader = LoaderManager.createLoader($('#essential-panel'));
        LoaderManager.showLoader(loader, function () {
            var columnManager = new ColumnManager('#essential');
            columnManager.addToColumn(0, new ColumnRow('Given name', new InputText(FIELD_GIVEN_NAME, function(id:number, value:string) {
                that.submitCallback('text', id, value);
            }, { placeholder: 'Given name' }, data.given_name)));
            columnManager.addToColumn(0, new ColumnRow('Middle name', new InputText(FIELD_MIDDLE_NAME, function(id:number, value:string) {
                that.submitCallback('text', id, value);
            }, { placeholder: 'Middle name' }, data.middle_name)));
            columnManager.addToColumn(0, new ColumnRow('Last name', new InputText(FIELD_LAST_NAME, function(id:number, value:string) {
                that.submitCallback('text', id, value);
            }, { placeholder: 'Last name' }, data.last_name)));
            columnManager.addToColumn(0, new ColumnRow('Email', new InputText(FIELD_EMAIL, function(id:number, value:string) {
                that.submitCallback('text', id, value);
            }, { placeholder: 'Email' }, data.email)));
            columnManager.addToColumn(1, new ColumnRow('Phone', new InputText(FIELD_PHONE, function(id:number, value:string) {
                that.submitCallback('text', id, value);
            }, { placeholder: 'Phone' }, data.phone)));
            columnManager.addToColumn(1, new ColumnRow('Record name', new InputText(FIELD_RECORD_NAME, function(id:number, value:string) {
                that.submitCallback('text', id, value);
            }, { placeholder: 'Record name' }, data.record_name)));
            columnManager.addToColumn(1, new ColumnRow('Record start date', new InputDate(FIELD_START_DATE, function(id:number, value:string) {
                that.submitCallback('date', id, value);
            }, { placeholder: 'Start date' }, Util.formatNumberDate(Util.parseSQLDate(data.start_date)))));
            columnManager.addToColumn(1, new ColumnRow('Record end date', new InputDate(FIELD_END_DATE, function(id:number, value:string) {
                that.submitCallback('date', id, value);
            }, { placeholder: 'End date' }, Util.formatNumberDate(Util.parseSQLDate(data.end_date)))));
            columnManager.addToColumn(2, new ColumnRow('Address', new InputTextMultiple(FIELD_ADDRESS, function(id:number, value:string[]) {
                that.submitCallback('text-multiple', id, value);
            }, { placeholder: 'Address line' }, data.address)));
            columnManager.render();
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    }

    private submitCallback(type:string, id:number, value:string|string[]|number|number[]) {
        console.log('ID: ' + type + ' ' + id + '. Values:');
        console.log(value);
    }

    private parseFields(data:FieldEditData[]) {
        var that = this;
        var loader = LoaderManager.createLoader($('#fields'));
        LoaderManager.showLoader(loader, function () {
            var count = data.length;
            var columnManager = new ColumnManager('#fields', 3, count);
            /**
             * interface FieldEditData {
    id:number,
    name:string,
    type:number,
    has_default:boolean,
    allow_other:boolean,
    is_multiple:boolean,
    defaults:string[],
    value:number|number[]|string|string[]
}
             */
            for (var i = 0; i < count; i++) {
                var field = data[i];
                var renderable;
                switch (field.type) {
                    case 1:
                        renderable = new InputNumber(field.id, function(id:number, value:string) { that.submitCallback('number', id, value); }, { placeholder: field.name }, <number> field.value);
                        break;
                    case 2:
                        var value = '';
                        var selected = field.value;
                        if(Util.isString(selected)) {
                            value = <string> selected;
                            selected = field.defaults.length;
                        }
                        if(field.has_default) {
                            renderable = new InputDropdown(field.id, function (id:number, value:string) {
                                that.submitCallback('dropdown', id, value);
                            }, field.defaults, <number|number[]> selected, field.allow_other, value, field.is_multiple);
                        } else if(field.is_multiple) {
                            renderable = new InputTextMultiple(field.id, function(id:number, value:string[]) { that.submitCallback('text-multiple', id, value); }, { placeholder: field.name }, <string[]> field.value)
                        } else {
                            renderable = new InputText(field.id, function(id:number, value:string) { that.submitCallback('text', id, value); }, { placeholder: field.name }, <string> field.value);
                        }
                        break;
                    case 3:
                        renderable = new InputDate(field.id, function(id:number, value:string) { that.submitCallback('date', id, value); }, { placeholder: field.name }, <string> field.value.toString());
                        break;
                    case 4:
                        renderable = new InputLongText(field.id, function(id:number, value:string) { that.submitCallback('long-text', id, value); }, { placeholder: field.name }, <string> field.value);
                        break;
                }
                columnManager.add(new ColumnRow(field.name, renderable));
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

        var viewButton = $('#record-view');

        viewButton.attr('href', Util.url('record/view/' + data.record_id));
    }

}

$(document).ready(function () {
    var single = new SingleView();
    var id = Util.extractId(window.location.toString());
    single.load(id);
});