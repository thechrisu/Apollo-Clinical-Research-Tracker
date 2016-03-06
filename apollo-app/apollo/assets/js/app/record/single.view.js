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
    return SingleView;
})();
$(document).ready(function () {
    new SingleView().load();
});
