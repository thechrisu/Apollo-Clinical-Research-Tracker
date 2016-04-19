///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../../typings/jquery.d.ts"/>
///<reference path="../../typings/bootbox.d.ts"/>
///<reference path="../columns.ts"/>
///<reference path="../inputs.ts"/>
/**
 * Records advanced search typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */
var RecordSearch = (function () {
    function RecordSearch() {
    }
    RecordSearch.prototype.load = function () {
        this.filters = {};
        this.filterCounter = 0;
        this.filterAmount = 0;
        this.filterParentNode = $('#filter-table');
        this.table = $('#table-body');
        this.pagination = $('#pagination');
        this.loaderField = LoaderManager.createLoader($('.filter.loader-ready'));
        this.loaderRecord = LoaderManager.createLoader($('.record.loader-ready'));
        this.page = 1;
        this.sort = 1;
        this.setup();
    };
    RecordSearch.prototype.setup = function () {
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
        this.addRecordClick();
        this.addTabFunctions();
        this.setupFilters();
    };
    RecordSearch.prototype.addRecordClick = function () {
        this.table.on('click', '.record-tr', function (e) {
            e.preventDefault();
            Util.to('record/view/' + $(this).data('id'));
        });
    };
    RecordSearch.prototype.addTabFunctions = function () {
        var that = this;
        $('#sort-tabs').on('click', '.sort-tab', function () {
            $('.sort-tab').removeClass('active');
            $(this).addClass('active');
            that.sort = $(this).data('sort');
            that.updateTable();
        });
    };
    RecordSearch.prototype.setupFilters = function () {
        var that = this;
        LoaderManager.showLoader(that.loaderField, function () {
            AJAX.get(Util.url('get/fields'), function (data) {
                that.fieldData = data;
                that.addFilter();
                that.updateTable();
                LoaderManager.hideLoader(that.loaderField);
            }, function (message) {
                Util.error('An error has occurred during the loading of the field data. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    };
    RecordSearch.prototype.removeFilter = function (id) {
        if (this.filterAmount > 1) {
            var filter = this.filters[id];
            delete this.filters[id];
            filter.getParentNode().remove();
            this.filterAmount--;
            this.updateTable();
        }
    };
    RecordSearch.prototype.addFilter = function () {
        var id = ++this.filterCounter;
        var filter = new Filter(id, this.fieldData, this.updateTable.bind(this), this.addFilter.bind(this), this.removeFilter.bind(this, id));
        this.filters[id] = filter;
        filter.render(this.filterParentNode);
        this.filterAmount++;
    };
    RecordSearch.prototype.updateTable = function () {
        var that = this;
        var states = [];
        for (var id in this.filters) {
            if (this.filters.hasOwnProperty(id)) {
                states.push(this.filters[id].getState());
            }
        }
        LoaderManager.showLoader(that.loaderRecord, function () {
            var postData = {
                page: that.page,
                sort: that.sort,
                states: states
            };
            AJAX.post(Util.url('post/search/'), postData, function (data) {
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
                LoaderManager.hideLoader(that.loaderRecord);
            }, function (message) {
                Util.error('An error has occurred during the loading of the list of records. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    };
    RecordSearch.prototype.renderTr = function (data) {
        var tr = $('<tr class="record-tr clickable" data-id="' + data.id + '"></tr>');
        [data.given_name, data.last_name, data.email, data.phone].forEach(function (string) {
            var td = $('<td></td>');
            var field = new DataText(Util.shortify(string, 50));
            field.renderPlain(td);
            tr.append(td);
        });
        this.table.append(tr);
    };
    return RecordSearch;
})();
/*

 1 -> Int -> Equal, Not Equal, Less Than, Greater Than
 2 -> Varchar -> Is Not Empty, Is Empty, Is Equal To, Is Not Equal to, Is Similar To
 2 -> Varchar Multiple -> Contains
 2 -> Varchar Default -> Is Equal To, Is Not Equal To
 3 -> Date -> Equal, Not Equal, Less Than, Greater Than
 4 -> Long Text -> Contains, Empty, Not Empty

 */
var Filter = (function () {
    function Filter(id, fieldData, updateCallback, addCallback, removeCallback) {
        var that = this;
        this.id = id;
        this.fieldData = fieldData;
        this.updateCallback = updateCallback;
        this.addCallback = addCallback;
        this.removeCallback = removeCallback;
        this.parentNode = Util.buildNode('tr');
        this.fieldNode = Util.buildNode('td', { width: '25%' });
        this.parentNode.append(this.fieldNode);
        this.relationNode = Util.buildNode('td', { width: '25%' });
        this.parentNode.append(this.relationNode);
        this.valueNode = Util.buildNode('td', { width: '25%' });
        this.parentNode.append(this.valueNode);
        this.actionNode = Util.buildNode('td', { width: '25%' });
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
    Filter.prototype.prepareFilter = function () {
        this.field = this.fieldData.data[0];
        var options = [];
        for (var i = 0; i < this.fieldData.data.length; i++) {
            options[i] = this.fieldData.data[i].name;
        }
        var fieldSelect = new InputDropdown(this.id, this.changeField.bind(this), options);
        fieldSelect.render(this.fieldNode);
        this.relation = 0;
        this.changeField(this.id, 0);
    };
    Filter.prototype.changeField = function (id, value) {
        this.field = this.fieldData.data[value];
        var relationSelect = new InputDropdown(this.id, this.changeRelation.bind(this), this.getRelations());
        this.relationNode.empty();
        relationSelect.render(this.relationNode);
        this.changeRelation(this.id, 0);
    };
    Filter.prototype.changeRelation = function (id, value) {
        this.relation = value;
        this.valueNode.empty();
        this.getInput().render(this.valueNode);
        this.updateCallback();
    };
    Filter.prototype.changeValue = function (id, value) {
        this.value = value;
        this.updateCallback();
    };
    Filter.prototype.getState = function () {
        var state = {};
        state['field'] = this.field.id;
        state['relation'] = this.relation;
        if (this.field.type == 3) {
            state['value'] = Util.toMysqlFormat(Util.parseNumberDate(this.value));
        }
        else {
            state['value'] = this.value;
        }
        return state;
    };
    Filter.prototype.getRelations = function () {
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
    };
    Filter.prototype.getInput = function () {
        switch (this.field.type) {
            case 2:
                switch (this.field.subtype) {
                    case 2:
                        // Multiple inputs
                        this.value = [];
                        if (this.relation == 0)
                            return new InputText(this.id, this.changeValue.bind(this), { placeholder: 'String' });
                        else {
                            return new InputDisabled(this.id, this.changeValue.bind(this), { placeholder: 'Not applicable' });
                        }
                    case 3:
                        // Dropdown
                        this.value = 0;
                        return new InputDropdown(this.id, this.changeValue.bind(this), this.field.defaults);
                    case 4:
                        // Dropdown + input
                        this.value = 0;
                        if (this.relation != 2)
                            return new InputDropdown(this.id, this.changeValue.bind(this), this.field.defaults);
                        else
                            return new InputText(this.id, this.changeValue.bind(this), { placeholder: 'Other value' });
                    case 5:
                        // Multiple options
                        this.value = 0;
                        return new InputDropdown(this.id, this.changeValue.bind(this), this.field.defaults);
                    default:
                        // Single input
                        this.value = '';
                        if (this.relation == 0 || this.relation == 1)
                            return new InputText(this.id, this.changeValue.bind(this), { placeholder: 'String' });
                        else {
                            return new InputDisabled(this.id, this.changeValue.bind(this), { placeholder: 'Not applicable' });
                        }
                }
            case 3:
                this.value = Util.formatNumberDate(new Date());
                return new InputDate(this.id, this.changeValue.bind(this), { placeholder: 'Date' }, Util.formatNumberDate(new Date()));
            case 4:
                this.value = '';
                if (this.relation == 0)
                    return new InputText(this.id, this.changeValue.bind(this), { placeholder: 'String' });
                else {
                    return new InputDisabled(this.id, this.changeValue.bind(this), { placeholder: 'Not applicable' });
                }
            default:
                this.value = 0;
                return new InputNumber(this.id, this.changeValue.bind(this), { placeholder: 'Integer value' }, 0);
        }
    };
    Filter.prototype.render = function (target) {
        target.append(this.parentNode);
    };
    Filter.prototype.getParentNode = function () {
        return this.parentNode;
    };
    return Filter;
})();
$(document).ready(function () {
    new RecordSearch().load();
});
