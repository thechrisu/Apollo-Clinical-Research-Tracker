///<reference path="ajax.ts"/>
///<reference path="scripts.ts"/>
///<reference path="../typings/jquery.d.ts"/>
///<reference path="../typings/bootbox.d.ts"/>
///<reference path="inputs.ts"/>
///<reference path="columns.ts"/>
/**
 * Fields index typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.3
 */
var FieldTable = (function () {
    function FieldTable() {
    }
    FieldTable.prototype.load = function () {
        this.table = $('#table-body');
        this.loader = LoaderManager.createLoader($('.table-responsive.loader-ready'));
        this.updateTable();
    };
    FieldTable.prototype.updateTable = function () {
        var that = this;
        LoaderManager.showLoader(that.loader, function () {
            AJAX.get(StringUtil.url('get/fields'), function (data) {
                if (data.data.length > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        that.renderTr(data.data[i]);
                    }
                }
                else {
                    that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
                }
                LoaderManager.hideLoader(that.loader);
            }, function (message) {
                WebUtil.error('An error has occurred during the loading of the list of fields. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    };
    FieldTable.prototype.updateCallback = function (type, id, value, button) {
        button.removeClass('btn-danger');
        button.removeClass('btn-success');
        button.addClass('btn-warning');
        button.html('<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Saving...');
        var data = {
            type: type,
            id: id,
            value: value
        };
        AJAX.post(StringUtil.url('post/field/update'), data, function (response) {
            button.removeClass('btn-warning');
            button.addClass('btn-success');
            button.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Changes saved.');
        }, function (message) {
            button.removeClass('btn-warning');
            button.addClass('btn-danger');
            button.html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Saving failed.');
            WebUtil.error('An error has occurred during the process of updating of the data. Error message: ' + message);
        });
    };
    FieldTable.prototype.renderTr = function (data) {
        var that = this;
        var tr = $('<tr class="record-tr' + (data.essential ? ' active' : '') + '" id="field-' + data.id + '" data-id="' + data.id + '"></tr>');
        var td = $('<td width="25%"></td>');
        var addButton = $('<button class="btn btn-block btn-sm btn-success disabled"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>No changes.</button>');
        var removeButton = $('<button class="btn btn-block btn-sm btn-danger' + (data.essential ? ' disabled' : '') + '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Hide</button>');
        if (!data.essential) {
            removeButton.on({
                click: function (e) {
                    e.preventDefault();
                    (function () {
                        var id = data.id;
                        bootbox.confirm('Are you sure you want to hide the field ' + data.name + '? The data won\'t be deleted and can be restored later.', function (result) {
                            if (result) {
                                AJAX.post(WebUtil.url('post/field/hide'), { id: id }, function (response) {
                                    addButton.removeClass('btn-warning');
                                    addButton.addClass('btn-success');
                                    addButton.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Field hidden.');
                                    $('#field-' + id).remove();
                                }, function (message) {
                                    addButton.removeClass('btn-warning');
                                    addButton.addClass('btn-danger');
                                    addButton.html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Hiding failed.');
                                    WebUtil.error('An error has occurred during the process of hiding of the field. Error message: ' + message);
                                });
                            }
                        });
                    })();
                }
            });
        }
        if (data.essential) {
            var field = new DataText(data.name);
            field.render(td);
        }
        else {
            var input = new InputText(data.id, function (id, value) {
                (function () {
                    var button = addButton;
                    that.updateCallback('name', id, value, button);
                })();
            }, { placeholder: 'Field name' }, data.name);
            input.render(td);
        }
        tr.append(td);
        var type = 'Integer';
        switch (data.type) {
            case 2:
                type = 'String';
                break;
            case 3:
                type = 'Date';
                break;
            case 4:
                type = 'Long text';
                break;
        }
        td = $('<td width="30%"></td>');
        var subtype = '<span class="undefined">None</span>';
        if (data.type == 2) {
            var defaults = false;
            switch (data.subtype) {
                case 1:
                    subtype = 'Single input';
                    break;
                case 2:
                    subtype = 'Multiple inputs';
                    break;
                case 3:
                    subtype = 'Dropdown';
                    defaults = true;
                    break;
                case 4:
                    subtype = 'Dropdown & input';
                    defaults = true;
                    break;
                case 5:
                    subtype = 'Multiple options';
                    defaults = true;
                    break;
            }
        }
        tr.append('<td width="20%">' + type + '&nbsp;&nbsp; <span class="undefined">/</span> &nbsp;&nbsp;' + subtype + '</td>');
        if (defaults) {
            var defaultsInput = new InputTextMultiple(data.id, function (id, value) {
                that.updateCallback('defaults', id, value, addButton);
            }, { placeholder: 'Default value' }, data.defaults);
            defaultsInput.render(td);
        }
        else {
            td.html('<span class="undefined">Not applicable</span>');
        }
        tr.append(td);
        var row = $('<div class="row fields-buttons"></div>');
        row.append($('<div class="col-md-7"></div>').append(addButton));
        row.append($('<div class="col-md-5"></div>').append(removeButton));
        tr.append($('<td width="25%"></td>').append(row));
        this.table.append(tr);
    };
    return FieldTable;
})();
$(document).ready(function () {
    new FieldTable().load();
    $('#add-field').click(function (e) {
        e.preventDefault();
        bootbox.dialog({
            title: 'Adding a new field',
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
                        var name = modal.find('#add-field-name').val();
                        var type = modal.find('#add-field-type').val();
                        AJAX.post(StringUtil.url('post/field/add'), {
                            name: name,
                            type: type
                        }, function (response) {
                            WebUtil.to('field');
                        }, function (message) {
                            WebUtil.error('An error has occurred during the process of adding of a new field. Error message: ' + message);
                        });
                    }
                }
            }
        });
    });
});
