///<reference path="ajax.ts"/>
///<reference path="scripts.ts"/>
///<reference path="jquery.d.ts"/>
///<reference path="bootbox.d.ts"/>
///<reference path="inputs.ts"/>
/**
 * Fields index typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

interface FieldData {
    id:number,
    essential:boolean,
    name:string,
    type:number,
    subtype:number, /*Only for type 2 (varchar): 1->Input, 2->Multiple inputs, 3->Dropdown, 4->Dropdown + Input, 5->Multiple dropdown*/
    defaults:string[],
}
interface TableData {
    error:Error,
    data:FieldData[]
}

class FieldTable {

    private table:JQuery;
    private loader:number;

    public load() {
        this.table = $('#table-body');
        this.loader = LoaderManager.createLoader($('.table-responsive.loader-ready'));
        this.setup();
        this.updateTable();
    }

    private setup() {
        var that = this;
    }

    private updateTable() {
        var that = this;
        LoaderManager.showLoader(that.loader, function () {
            AJAX.get(Util.url('get/fields'), function (data:TableData) {
                if(data.data.length > 0) {
                    for(var i = 0; i < data.data.length; i++) {
                        that.renderTr(data.data[i]);
                    }
                } else {
                    that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
                }
                LoaderManager.hideLoader(that.loader);
            }, function (message:string) {
                Util.error('An error has occurred during the loading of the list of fields. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
    }

    private renderTr(data:FieldData) {
        var that = this;
        var tr = $('<tr class="record-tr" data-id="' + data.id + '"></tr>');
        var input = new InputText(data.id, function (id:number, value:string) {
            //TODO: Callback
        }, {placeholder: 'Field name'}, <string> data.name);
        var td = $('<td></td>');
        input.render(td);
        tr.append(td);
        var type = 'Integer';
        switch(data.type) {
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
        tr.append('<td>' + type + '</td>');
        var subtype = '<span class="undefined">Not applicable</span>';
        if(data.type == 2) {
            switch(data.subtype) {
                case 1:
                    subtype = 'Single input';
                    break;
                case 2:
                    subtype = 'Multiple inputs';
                    break;
                case 3:
                    subtype = 'Dropdown';
                    break;
                case 4:
                    subtype = 'Dropdown & input';
                    break;
                case 5:
                    subtype = 'Multiple options';
                    break;
            }
        }
        tr.append('<td>' + subtype + '</td>');
        tr.append('<td>123</td>');
        this.table.append(tr);
    }

}

$(document).ready(function () {
    new FieldTable().load();
});