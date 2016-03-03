/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

/**
 * Responsible for displaying a single record.
 * TODO: Implement rest of details, fix display (should do nice columns)
 */

obj: fakeJSON_obj = {
    "error": null,
    "essential": {
        "given_name": "James",
        "last_name": "Bond",
        "email": "iLove@moneypenny.co.uk",
        "phone": "+44 007",
        "record_number": 7,
        "record_name": "Record name",
        "record_ids": [1, 2, 3, 4, 7, 1729],
        "record_names": ["something", "something other", "secret", "GOSH", "top secret", "joke"]
    },
    "numberOfFields": 8,
    "fields": [
        {
            "name": "Supervisor",
            "type": "String",
            "value": "M"
        },
        {
            "name": "Cars",
            "type": "String",
            "value": "Aston Martin DB5"
        },
        {
            "name": "Funding Source",
            "type": "String",
            "value": "This information is top secret"
        },
        {
            "name": "Payband",
            "type": "String",
            "value": "7"
        },
        {
            "name": "References",
            "type": "Text",
            "value": "Mister Bond is one of our nicest employees. In fact, he even developed new applications in conjunction with Q. He is always punctual"
        },
        {
            "name": "Specialty",
            "type": "String",
            "value": "Making cheesy comments"
        },
        {
            "name": "Start Date",
            "type": "Date",
            "value": 1456940959
        },
        {
            "name": "End Date",
            "type": "Date",
            "value": 1456941022
        }
    ]
};

JSON: fakeJSON = <JSON>fakeJSON_obj;

var numCols = 3;

$(document).ready(function () {
    var path = window.location.pathname;
    var recordNumber = getEnding(path);
    getRecordFromServer(recordNumber);
    AJAX.fakeGet(fakeJSON, function (data) {
        var fullName = data.essential.given_name + ' ' + data.essential.last_name;
        updateBreadcrumbs(fullName, data.essential.record_name, data.essential.record_names, data.essential.record_ids);
        displayEssentialInfo(data.essential);
        parseAllFields(data);
        formatRows();
    }, function (message) {
        console.log(message);
        //TODO Add errorhandling

    });

    function updateBreadcrumbs(personName, recordName, recordNames, recordIds) {
        setTitle(personName, recordName);
        var bc = $('#nav-breadcrumbs');
        //bc.html('');
        bc.append("<li>" + personName + "</li>");
        var links = getLinks(recordNames, recordIds);
        bc.append(getEditButton());
        var dd = getDropdownWithItems(links);
        bc.append(dd);
        bc.append('<br>');
        bc.append("<h1 style='display: inline'>" + recordName + "</h1><h6 style='display: inline'>" + personName + "</h6>");
    };

    function getEditButton(){
        var symbol = $("<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>");
        var link = $("<a href=\"" + window.location.origin + "\/record\/edit\/" + getEnding(window.location.pathname) + "\" class='btn' />").append(symbol);
        return link;
    }

    function getRecordFromServer(recordId) {
        //TODO put the ajax request in here, extract the logic in it into separate functions
    };

    function getDropdownWithItems(items) {
        var itemList = "";
        for (var i = 0; i < items.length; i++) {
            itemList += "<li>" + items[i] + "</li>";
        }
        var dropdown = $('<div class="btn-group pull-right"> \
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> \
            Browse Records <span class="caret"></span> \
        </button> \
        <ul class="dropdown-menu" role="menu" id="dropdown-elems">' + itemList + '</ul></div>');

        return dropdown;
    };

    function displayEssentialInfo(data) {
        var g = $('#recordGeneric');
        g.append("<div class='panel panel-default' id='genericInfo'></div>");
        g = $('#genericInfo');
        g.append("<div class='panel-heading'><h5 class='panel-title'>Basic information</h5></div>");
        g.append("<div class='panel-body' id='basicInfo'></div>");
        g = $('#basicInfo');
        g.append("<div class='col-md-3'><h5 style='display: inline'>" + data.given_name + ' ' + data.last_name + "</h5><small style='display: inline'> name</small></div>");
        g.append("<div class='col-md-3'><h5 style='display: inline'>" + data.email + "</h5><small style='display: inline'> email</small></small></div>");
        g.append("<div class='col-md-3'><h5 style='display: inline'>" + data.phone + "</h5><small style='display: inline'> phone</small></div>");
        g.append("<div class='col-md-3'><h5 style='display: inline'>" + data.record_name + "</h5><small style='display: inline'> record name</small></div>");
    }

    function parseAllFields(data) {
        if (data.numberOfFields != data.fields.length) {
            console.error("Number of fields different than expected.");
        } else {
            console.log("Printing... " + data.numberOfFields);
            var colWidthParam = "col-md-" + Math.floor(12 / numCols);
            makeDetailPanel();
            makeContainers(numCols, colWidthParam);
            var currentField;
            for (currentField = 0; currentField < data.numberOfFields; currentField++) {
                var co = currentField % numCols;
                var field = data.fields[currentField];
                //console.log(col[co].find('#tableRow'));
                console.log($('#tableRow' + co));
                $('#tableRow' + co).append(parseField(field));
            }

        }
    }

    function formatRows(){
        //TODO
    }

    function makeContainers(numberOfContainers, colWidth) {
        $('#detailedPanelContent').append('<div class="row" id="details"></div>');
        for (var j = 0; j < numberOfContainers; j++) {
            var tb = "tableRow" + j;
            var container = $("<div class=\"" + colWidth + "\" id=\"" + tb + "\"></div>");
            $('#details').append(container);
        }
    }

    function makeDetailPanel() {
        var g = $('#recordDetails');
        g.append("<div class='panel panel-default' id='detailedInfoPanel'></div>");
        g = $('#detailedInfoPanel');
        g.append("<div class='panel-heading'><h5 class='panel-title'>Detailed information</h5></div>");
        g.append("<div class='panel-body' id='detailedPanelContent'></div>");
    }

    //TODO: Care about edge cases (what if array is empty), refactor to remove duplication
    function parseField(field) {
        field.name = field.name.toLowerCase();
        field.type = field.type.toLowerCase();
        switch (field.type) {
            case 'string':
            case 'integer':
            case 'int':
            case 'text':
                return getLine(field.type, field.value, field.name);
            case 'stringar':
            case 'integerar':
            case 'intar':
                return getLineAr(field.type, field.value, field.name);
            case 'datetime':
            case 'date':
                return getLine(field.type, getStringFromEpochTime(field.value), field.name);
            case 'datetimear':
            case 'datear':
                for (var i = 0; i < field.value.length; i++) {
                    field.value[i] = getStringFromEpochTime(field.value[i]);
                }
                return getLineAr(field.type, field.value, field.name);
            default:
                console.error("Could not find out what type the field is. Field name: " + field.name);
        }
    }

    function setTitle(personName, recordName) {
        document.title = personName + ' | ' + recordName;
    };

    function getLine(type, value, name) {
        return $("<div class=\"" + type + "Col\"><h5>" + value + "</h5><small>" + name + "</small></div>");
    }

    function getLineWithoutName(type, value) {
        return $("<div class=\"" + type + "Col\"><h5>" + value + "</h5></div>");
    }

    function getLineAr(type, array, name) {
        var ret = [$("div class=\"" + type + "HeadCol\"><h5>" + array.value[0] + "</h5><small>" + name + "</small></div>")];

        for (var i = 1; i < array.length; i++) {
            ret.push(getLineWithoutName(type, array.value[i]));

        }
        return ret;
    }

    function getLinks(names, recordItems) {
        var as = [];
        for (var i = 0; i < names.length; i++) {
            as.push("<a href=\"" + window.location.origin + "\/record\/view\/" + recordItems[i] + "\">" + names[i] + "</a>");
        }
        return as;
    };

    function getStringFromEpochTime(time) {
        var DateObj = $.parseJSON('{"date_created":"' + time + '"}');
        var myDate = new Date(1000 * DateObj.date_created);
        var dateS = myDate.getFullYear() + '-' + myDate.getMonth() + '-' + myDate.getDay();
        return dateS;
    }

    function getEnding(url) {
        var re = new RegExp("[^\/]+(?=\/*$)|$"); //Matches anything that comes after the last slash (and anything before final slashes)
        var base = re.exec(url);
        if (base == null) {
            console.error("URL ending could not be found out correctly, URL: " + url);
        } else {
            return base[0];
        }
    };
});

