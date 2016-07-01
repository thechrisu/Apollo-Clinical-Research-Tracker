///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../../typings/jquery.d.ts"/>
///<reference path="../../typings/bootbox.d.ts"/>
///<reference path="../columns.ts"/>
///<reference path="../inputs.ts"/>
///<reference path="../apollopagination.ts"/>
/**
 * Records advanced search typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.3
 */

interface FieldData {
    id:number,
    essential:boolean,
    name:string,
    type:number,
    subtype:number, /*Only for type 2 (varchar): 1->Input, 2->Multiple inputs, 3->Dropdown, 4->Dropdown + Input, 5->Multiple dropdown*/
    defaults:string[],
}
interface FieldInputData {
    error:Error,
    data:FieldData[]
}
interface RecordData {
    id:number,
    given_name:string,
    middle_name:string,
    last_name:string,
    email:string,
    phone:string,
}
interface TableData {
    error:Error,
    count:number,
    data:RecordData[]
}
interface FilterState {
    field:number,
    relation:number,
    value:string|string[]|number
}

class RecordSearch {

    private fieldData:FieldInputData;
    private filters:Filters;
    private filterCounter:number;
    private filterAmount:number;

    private filterParentNode:JQuery;
    private table:JQuery;
    private pagination:ApolloPagination;
    private loaderField:number;
    private loaderRecord:number;
    private page:number;
    private sort:number;

    public load() {
        this.filters = {};
        this.filterCounter = 0;
        this.filterAmount = 0;
        this.filterParentNode = $('#filter-table');
        this.table = $('#table-body');
        this.loaderField = LoaderManager.createLoader($('.filter.loader-ready'));
        this.loaderRecord = LoaderManager.createLoader($('.record.loader-ready'));
        this.page = 1;
        this.sort = 1;
        this.setup();
    }

    private setup() {
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
        this.addRecordClick();
        this.addTabFunctions();
        this.setupFilters();
    }

    private addRecordClick() {
        this.table.on('click', '.record-tr', function (e) {
            e.preventDefault();
            WebUtil.to('record/view/' + $(this).data('id'));
        });
    }

    private addTabFunctions() {
        var that = this;
        $('#sort-tabs').on('click', '.sort-tab', function () {
            $('.sort-tab').removeClass('active');
            $(this).addClass('active');
            that.sort = $(this).data('sort');
            that.updateTable();
        });
    }

    private setupFilters() {
        var that = this;
        LoaderManager.showLoader(that.loaderField, function () {
            AJAX.get(StringUtil.url('get/fields'), function (data:FieldInputData) {
                that.fieldData = data;
                that.addFilter();
                that.updateTable();
                LoaderManager.hideLoader(that.loaderField);
            }, function (message:string) {
                WebUtil.error('An error has occurred during the loading of the field data. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    }

    private removeFilter(id:number) {
        if(this.filterAmount > 1) {
            var filter = this.filters[id];
            delete this.filters[id];
            filter.getParentNode().remove();
            this.filterAmount--;
            this.updateTable();
        }
    }

    private addFilter() {
        var id = ++this.filterCounter;
        var filter = new Filter(id, this.fieldData, this.updateTable.bind(this), this.addFilter.bind(this), this.removeFilter.bind(this, id));
        this.filters[id] = filter;
        filter.render(this.filterParentNode);
        this.filterAmount++;
    }

    private updateTable() {
        var that = this;
        var states = <FilterState[]> [];
        for(var id in this.filters) {
            if(this.filters.hasOwnProperty(id)) {
                states.push(this.filters[id].getState());
            }
        }
        LoaderManager.showLoader(that.loaderRecord, function () {
            var postData = {
                page: that.page,
                sort: that.sort,
                states: states,
            };
            AJAX.post(StringUtil.url('post/search/'), postData, function (data:TableData) {
                var tooFewElements = that.pagination.updateNumPagesSmart(data.count);
                if(tooFewElements)
                    return;
                that.table.html('');
                if (data.count > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        that.renderTr(data.data[i]);
                    }
                } else {
                    that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
                }
                LoaderManager.hideLoader(that.loaderRecord);
            }, function (message:string) {
                WebUtil.error('An error has occurred during the loading of the list of records. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    }

    private renderTr(data:RecordData) {
        var tr = $('<tr class="record-tr clickable" data-id="' + data.id + '"></tr>');
        [data.given_name, data.last_name, data.email, data.phone].forEach(function (string) {
            var td = $('<td></td>');
            var field = new DataText(StringUtil.shortify(string, 50));
            field.renderPlain(td);
            tr.append(td);
        });
        this.table.append(tr);
    }

}

interface Filters {
    [id:number]:Filter
}

/*

 1 -> Int -> Equal, Not Equal, Less Than, Greater Than
 2 -> Varchar -> Is Not Empty, Is Empty, Is Equal To, Is Not Equal to, Is Similar To
 2 -> Varchar Multiple -> Contains
 2 -> Varchar Default -> Is Equal To, Is Not Equal To
 3 -> Date -> Equal, Not Equal, Less Than, Greater Than
 4 -> Long Text -> Contains, Empty, Not Empty

 */

class Filter implements Renderable {

    private id:number;
    private fieldData:FieldInputData;
    private updateCallback:Function;
    private addCallback:Function;
    private removeCallback:Function;

    private parentNode:JQuery;
    private fieldNode:JQuery;
    private relationNode:JQuery;
    private valueNode:JQuery;
    private actionNode:JQuery;

    private field:FieldData;
    private relation:number;
    private value:string|string[]|number;

    public constructor(id:number, fieldData:FieldInputData, updateCallback:Function, addCallback:Function, removeCallback:Function) {
        var that = this;
        this.id = id;
        this.fieldData = fieldData;
        this.updateCallback = updateCallback;
        this.addCallback = addCallback;
        this.removeCallback = removeCallback;
        this.parentNode = WebUtil.buildNode('tr');
        this.fieldNode = WebUtil.buildNode('td', {width: '25%'});
        this.parentNode.append(this.fieldNode);
        this.relationNode = WebUtil.buildNode('td', {width: '25%'});
        this.parentNode.append(this.relationNode);
        this.valueNode = WebUtil.buildNode('td', {width: '25%'});
        this.parentNode.append(this.valueNode);
        this.actionNode = WebUtil.buildNode('td', {width: '25%'});
        var addButton = $('<button class="btn btn-block btn-sm btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add</button>');
        addButton.on({
            click: function (e) {
                e.preventDefault();
                that.addCallback();
            }
        });
        var removeButton = $('<button class="btn btn-block btn-sm btn-warning"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span>Remove</button>');
        removeButton.on({
            click: function (e) {
                e.preventDefault();
                that.removeCallback();
            }
        });
        var row = $('<div class="row search-buttons"></div>');
        row.append($('<div class="col-md-6"></div>').append(addButton));
        row.append($('<div class="col-md-6"></div>').append(removeButton));
        this.actionNode.append(row);
        this.parentNode.append(this.actionNode);
        this.prepareFilter();
    }

    private prepareFilter() {
        this.field = this.fieldData.data[0];
        var options = [];
        for (var i = 0; i < this.fieldData.data.length; i++) {
            options[i] = this.fieldData.data[i].name;
        }
        var fieldSelect = new InputDropdown(this.id, this.changeField.bind(this), options);
        fieldSelect.render(this.fieldNode);
        this.relation = 0;
        this.changeField(this.id, 0);
    }

    private changeField(id:number, value:number) {
        this.field = this.fieldData.data[value];
        var relationSelect = new InputDropdown(this.id, this.changeRelation.bind(this), this.getRelations());
        this.relationNode.empty();
        relationSelect.render(this.relationNode);
        this.changeRelation(this.id, 0);
    }

    private changeRelation(id:number, value:number) {
        this.relation = value;
        this.valueNode.empty();
        this.getInput().render(this.valueNode);
        this.updateCallback();
    }

    private changeValue(id:number, value:string) {
        this.value = value;
        this.updateCallback();
    }

    public getState():FilterState {
        var state = <FilterState> {};
        state['field'] = this.field.id;
        state['relation'] = this.relation;
        if(this.field.type == 3) {
            state['value'] = DateUtil.toMysqlFormat(DateUtil.parseNumberDate(<string> this.value));
        } else {
            state['value'] = this.value;
        }
        return state;
    }

    private getRelations():string[] {
        var relations = [];
        switch (this.field.type) {
            case 2:
                switch (this.field.subtype) {
                    case 1:
                        // Single input
                        relations = [
                            'Contains',
                            'Does not contain',
                            'Is equal to',
                            'Is not equal to',
                            'Is empty',
                            'Is not empty'
                        ];
                        break;
                    case 2:
                        // Multiple inputs
                        relations = [
                            'Contains',
                            'Does not contain',
                            'Is empty',
                            'Is not empty'
                        ];
                        break;
                    case 3:
                        // Dropdown
                        relations = [
                            'Is equal to',
                            'Is not equal to'
                        ];
                        break;
                    case 4:
                        // Dropdown + input
                        relations = [
                            'Is equal to',
                            'Is not equal to',
                            'Other contains',
                        ];
                        break;
                    case 5:
                        // Multiple options
                        relations = [
                            'Contains',
                            'Does not contain'
                        ];
                        break;
                }
                break;
            case 4:
                relations = [
                    'Contains',
                    'Does not contain',
                    'Is empty',
                    'Is not empty'
                ];
                break;
            default:
                relations = [
                    'Is equal to',
                    'Is not equal to',
                    'Is less than',
                    'Is greater than'
                ];
                break;
        }
        return relations;
    }

    private getInput():InputField {
        switch (this.field.type) {
            case 2:
                switch (this.field.subtype) {
                    case 2:
                        // Multiple inputs
                        this.value = [];
                        if (this.relation == 0)
                            return new InputText(this.id, this.changeValue.bind(this), {placeholder: 'String'});
                        else {
                            return new InputDisabled(this.id, this.changeValue.bind(this), {placeholder: 'Not applicable'});
                        }
                    case 3:
                        // Dropdown
                        this.value = 0;
                        return new InputDropdown(this.id, this.changeValue.bind(this), this.field.defaults);
                    case 4:
                        // Dropdown + input
                        this.value = 0;
                        if(this.relation != 2)
                            return new InputDropdown(this.id, this.changeValue.bind(this), this.field.defaults);
                        else
                            return new InputText(this.id, this.changeValue.bind(this), {placeholder: 'Other value'});
                    case 5:
                        // Multiple options
                        this.value = 0;
                        return new InputDropdown(this.id, this.changeValue.bind(this), this.field.defaults);
                    default:
                        // Single input
                        this.value = '';
                        if (this.relation == 0 || this.relation == 1)
                            return new InputText(this.id, this.changeValue.bind(this), {placeholder: 'String'});
                        else {
                            return new InputDisabled(this.id, this.changeValue.bind(this), {placeholder: 'Not applicable'});
                        }
                }
            case 3:
                this.value = DateUtil.formatNumberDate(new Date());
                return new InputDate(this.id, this.changeValue.bind(this), {placeholder: 'Date'}, DateUtil.formatNumberDate(new Date()));
            case 4:
                this.value = '';
                if (this.relation == 0)
                    return new InputText(this.id, this.changeValue.bind(this), {placeholder: 'String'});
                else {
                    return new InputDisabled(this.id, this.changeValue.bind(this), {placeholder: 'Not applicable'});
                }
            default:
                this.value = 0;
                return new InputNumber(this.id, this.changeValue.bind(this), {placeholder: 'Integer value'}, 0);
        }
    }

    public render(target:JQuery) {
        target.append(this.parentNode);
    }

    public getParentNode():JQuery {
        return this.parentNode;
    }

}

$(document).ready(function () {
    new RecordSearch().load();
});