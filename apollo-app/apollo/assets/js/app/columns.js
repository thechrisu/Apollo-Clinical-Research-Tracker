///<reference path="jquery.d.ts"/>
/**
 * Column manager typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
var ColumnManager = (function () {
    function ColumnManager(target, columnCount) {
        if (columnCount === void 0) { columnCount = 3; }
        this.targetSelector = target;
        this.target = $(target);
        this.container = $('<div class="row top-buffer"></div>');
        this.columnCount = columnCount;
        this.columns = [];
        for (var i = 0; i < columnCount; i++) {
            this.columns[i] = new Column(this.container);
        }
    }
    ColumnManager.prototype.addToColumn = function (index, row) {
        this.columns[index].addToBack(row);
    };
    ColumnManager.prototype.render = function (overwriteContent) {
        if (overwriteContent === void 0) { overwriteContent = true; }
        if (overwriteContent) {
            this.target.html('');
        }
        for (var i = 0; i < this.columnCount; i++) {
            this.columns[i].render();
        }
        this.target.append(this.container);
    };
    return ColumnManager;
})();
var Column = (function () {
    function Column(target) {
        this.target = target;
        this.rows = [];
    }
    Column.prototype.addToFront = function (row) {
        this.rows.unshift(row);
    };
    Column.prototype.addToBack = function (row) {
        this.rows.push(row);
    };
    Column.prototype.render = function () {
        var column = $('<table class="table no-border-top"></table>');
        for (var i = 0; i < this.rows.length; i++) {
            this.rows[i].render(column);
        }
        var responsive = $('<div class="table-responsive"></div>');
        responsive.append(column);
        var bootstrapColumn = $('<div class="col-md-4"></div>');
        bootstrapColumn.append(responsive);
        this.target.append(bootstrapColumn);
    };
    return Column;
})();
var ColumnRow = (function () {
    function ColumnRow(key, value) {
        this.key = key;
        this.value = value;
    }
    ColumnRow.prototype.render = function (target) {
        if (this.value instanceof Array) {
            var length = this.value.length;
            for (var k = 0; k < length; k++) {
                var rowHTML = $('<tr></tr>');
                if (k == 0) {
                    rowHTML.append($('<td rowspan="' + length + '">' + this.decorateKey(this.key) + '</td>'));
                }
                rowHTML.append($('<td>' + this.decorateValue(this.value[k]) + '</td>'));
                target.append(rowHTML);
            }
        }
        else {
            var rowHTML = $('<tr></tr>');
            rowHTML.append($('<td>' + this.decorateKey(this.key) + '</td>'));
            rowHTML.append($('<td>' + this.decorateValue(this.value) + '</td>'));
            target.append(rowHTML);
        }
    };
    ColumnRow.prototype.decorateKey = function (key) {
        key = '<small>' + key + '</small>';
        return key;
    };
    ColumnRow.prototype.decorateValue = function (value) {
        if (value == null) {
            value = '<span class="undefined">None</span>"';
        }
        else {
            value = '<strong>' + value + '</strong>';
        }
        return value;
    };
    return ColumnRow;
})();
