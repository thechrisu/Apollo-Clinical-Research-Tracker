///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
///<reference path="../columns.ts"/>
/**
 * Single record view typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

interface EssentialData {
    given_name:string,
    middle_name:string,
    last_name:string,
    email:string,
    address:string[],
    phone:string,
    record_id:number,
    record_name:string,
    record_ids:number[],
    record_names:string[],

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

    public load() {
        var that = this;
        AJAX.get(Util.url('get/record'), function (data:RecordData) {
            var breadcrumbs = $('#nav-breadcrumbs');
            breadcrumbs.find('li:nth-child(3)').text(data.essential.given_name + ' ' + data.essential.last_name);
            breadcrumbs.find('li:nth-child(4)').text('Record #' + data.essential.record_id + ': ' + data.essential.record_name);
            that.parseEssentials(data.essential);
            that.parseFields(data.data);
        }, function (message:string) {
            Util.error('An error has occurred during the loading of single record data. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }

    public parseEssentials(data:EssentialData) {
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
    }

    public parseFields(data:FieldData[]) {
        var loader = LoaderManager.createLoader($('#fields'));
        LoaderManager.showLoader(loader, function () {
            var count = data.length;
            var columnManager = new ColumnManager('#fields', 3, count);
            for(var i = 0; i < count; i++) {
                var field = data[i];
                columnManager.add(new ColumnRow(field.name, field.value));
            }
            columnManager.render(false);
            LoaderManager.hideLoader(loader, function () {
                LoaderManager.destroyLoader(loader);
            });
        });
    }

}

$(document).ready(function () {
    new SingleView().load();
});