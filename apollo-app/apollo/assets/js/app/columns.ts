///<reference path="../typings/jquery.d.ts"/>
///<reference path="scripts.ts"/>
/**
 * Column manager typescript
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.7
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
        if (this.totalRows == null) {
            var bestColumn = this.columns[0];
            for (var i = 0; i < this.columnCount; i++) {
                var column = this.columns[i];
                if (column.countRows() < bestColumn.countRows()) {
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
    private value:Renderable;

    constructor(key:string, value:Renderable) {
        this.key = key;
        this.value = value;
    }

    public render(target:JQuery) {
        var rowHTML = $('<tr></tr>');
        rowHTML.append($('<td><small>' + this.key + '</small></td>'));
        var valueTD = $('<td></td>');
        this.value.render(valueTD);
        rowHTML.append(valueTD);
        target.append(rowHTML);
    }

}

abstract class DataField implements Renderable {

    private parentNode:JQuery;
    private value:JQuery;

    public constructor(value:any) {
        this.parentNode = $('<div class="apollo-data-container"></div>');
        this.value = this.parse(value);
        this.parentNode.append(this.value);
    }

    private parse(value:any):JQuery {
        if (value == null || value.length == 0) {
            return $('<span class="undefined">None</span>');
        }
        return this.decorate(value);
    }

    protected abstract decorate(value:any):JQuery;

    public render(target:JQuery) {
        target.append(this.parentNode);
    }

    public getPlain():string {
        return this.value.text();
    }

    public renderPlain(target:JQuery) {
        if((<string>this.value.prop("tagName")).toLowerCase() == 'strong') {
            target.text(this.value.text());
        } else {
            target.append(this.value);
        }
    }
}

class DataText extends DataField {
    protected decorate(value:string):JQuery {
        return WebUtil.buildNode('strong').text(value);
    }
}

class DataTextMultiple extends DataField {
    protected decorate(value:string[]):JQuery {
        var node = WebUtil.buildNode('div');
        for (var i = 0; i < value.length; i++) {
            var string = value[i];
            var line = $('<div class="apollo-data-text-multiple"></div>');
            if (string == null || string.length == 0) {
                line.html('<span class="undefined">None</span>');
            } else {
                line.append(WebUtil.buildNode('strong').text(string))
            }
            node.append(line);
        }
        return node;
    }
}

class DataDate extends DataField {
    protected decorate(value:Date|string):JQuery {
        if (Util.isString(value)) {
            value = DateUtil.parseSQLDate(<string> value);
        }
        return WebUtil.buildNode('strong').text(DateUtil.formatDate(<Date> value));
    }
}

class DataDateShort extends DataField {
    protected decorate(value:Date|string):JQuery {
        if (Util.isString(value)) {
            value = DateUtil.parseSQLDate(<string> value);
        }
        return WebUtil.buildNode('strong').text(DateUtil.formatShortDate(<Date> value));
    }
}

interface DateRange {
    startDate:Date|string,
    endDate:Date|string
}

class DataDateRange extends DataField {

    public constructor(value:DateRange) {
        super(value);
    }

    protected decorate(data:DateRange):JQuery {
        var startDate = new DataDateShort(data.startDate).getPlain();
        var endDate = new DataDateShort(data.endDate).getPlain();
        return WebUtil.buildNode('strong').text(startDate + ' - ' + endDate);
    }

}

class DataLongText extends DataField {
    protected decorate(value:string):JQuery {
        return WebUtil.buildNode('strong').text(value.replace(/\n/gi, '<br>'));
    }
}