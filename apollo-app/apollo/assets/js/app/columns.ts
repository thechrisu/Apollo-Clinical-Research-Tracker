///<reference path="jquery.d.ts"/>
/**
 * Column manager typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
 */

class ColumnManager {

    private targetSelector:string;
    private target:JQuery;
    private container:JQuery;
    private columnCount:number;
    private columns:Column[];
    private totalRows:number;

    constructor(target:string, columnCount:number = 3, totalRows:number = null) {
        this.targetSelector = target;
        this.target = $(target);
        this.container = $('<div class="row top-buffer"></div>');
        this.columnCount = columnCount;
        this.columns = [];
        for (var i = 0; i < columnCount; i++) {
            this.columns[i] = new Column(this.container);
        }
        this.totalRows = totalRows;
    }

    public add(row:ColumnRow) {
        if(this.totalRows == null) {
            var bestColumn = this.columns[0];
            for (var i = 0; i < this.columnCount; i++) {
                var column = this.columns[i];
                if(column.countRows() < bestColumn.countRows()) {
                    bestColumn = column;
                }
            }
            bestColumn.addToBack(row);
        } else {
            for (var i = 0; i < this.columnCount; i++) {
                var column = this.columns[i];
                if (column.countRows() < this.totalRows / 3) {
                    column.addToBack(row);
                    break;
                }
            }
        }
    }

    public addToColumn(index:number, row:ColumnRow) {
        this.columns[index].addToBack(row);
    }

    public render(overwriteContent:boolean = true) {
        if (overwriteContent) {
            this.target.html('');
        }
        for (var i = 0; i < this.columnCount; i++) {
            this.columns[i].render();
        }
        this.target.append(this.container);
    }

}

class Column {

    private target:JQuery;
    private rows:ColumnRow[];

    constructor(target:JQuery) {
        this.target = target;
        this.rows = [];
    }

    public addToFront(row:ColumnRow) {
        this.rows.unshift(row);
    }

    public addToBack(row:ColumnRow) {
        this.rows.push(row);
    }

    public render() {
        var column = $('<table class="table no-border-top"></table>');
        for (var i = 0; i < this.rows.length; i++) {
            this.rows[i].render(column);
        }
        var responsive = $('<div class="table-responsive"></div>');
        responsive.append(column);
        var bootstrapColumn = $('<div class="col-md-4"></div>');
        bootstrapColumn.append(responsive);
        this.target.append(bootstrapColumn);
    }

    public countRows():number {
        return this.rows.length;
    }

}

class ColumnRow {

    private key:string;
    private value:string|string[];

    constructor(key:string, value:string|string[]) {
        this.key = key;
        this.value = value;
    }

    public render(target:JQuery) {
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
        } else {
            var rowHTML = $('<tr></tr>');
            rowHTML.append($('<td>' + this.decorateKey(this.key) + '</td>'));
            rowHTML.append($('<td>' + this.decorateValue(this.value) + '</td>'));
            target.append(rowHTML);
        }
    }

    public decorateKey(key:string):string {
        key = '<small>' + key + '</small>'
        return key;
    }

    public decorateValue(value:string):string {
        if (value == null) {
            value = '<span class="undefined">None</span>'
        } else {
            value = '<strong>' + value + '</strong>'
        }
        return value;
    }

}