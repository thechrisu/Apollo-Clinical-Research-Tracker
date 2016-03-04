/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

obj: fakeJSON_obj = {
    "error": null,
    "data": {
        "essential": {
            "given_name": "James",
            "last_name": "Bond",
            "email": "iLove@moneypenny.co.uk",
            "phone": "+44 007",
            "record_number": 7,
            "record_name": "Her majesty's secret weapon",
            "record_ids": [1, 2, 3, 4, 7, 1729],
            "record_names": ["something", "something other", "secret", "GOSH", "top secret", "joke"]
        },
        "numberOfFields": 8,
        "fields": [
            {
                "name": "Supervisor",
                "type": "String",
                "defaults": null,
                "allow_other": true,
                "values": "M"
            },
            {
                "name": "Cars",
                "type": "StringAr",
                "defaults": null,
                "allow_other": true,
                "values": "Aston Martin DB5"
            },
            {
                "name": "Funding Source",
                "type": "String",
                "defaults": null,
                "allow_other": true,
                "values": "M"
            },
            {
                "name": "Payband",
                "type": "Integer",
                "defaults": [0, 1, 2, 3, 4, 5, 6, 7],
                "allow_other": false,
                "values": 7
            },
            {
                "name": "References",
                "type": "Text",
                "has_default": false,
                "allow_other": false,
                "values": "Mister Bond is one of our nicest employees. In fact, he even developed new applications in conjunction with Q. He is always punctual"
            },
            {
                "name": "Specialty",
                "type": "String",
                "has_default": false,
                "allow_other": false,
                "values": "Making cheesy comments"
            },
            {
                "name": "Start Date",
                "type": "Date",
                "has_default": false,
                "allow_other": false,
                "values": 1456940959
            },
            {
                "name": "End Date",
                "type": "Date",
                "has_default": false,
                "allow_other": false,
                "values": 1456941022
            }
        ]
    }
};


JSON: fakeJSON = <JSON>fakeJSON_obj;

var numCols = 2;

$(document).ready(function () {
    var path = window.location.pathname;
    var recordNumber = getEnding(path);
    var loader = $('#loader');
    loader.fadeIn(200);
    getRecordFromServer(recordNumber);
    AJAX.fakeGet(fakeJSON, function (data) {
        var data = data.data;
        var fullName = data.essential.given_name + ' ' + data.essential.last_name;
        updateBreadcrumbs(fullName, data.essential.record_name);
        displayEssentialInfo(data.essential);
        var cal = getCalendar(data.fields[6].values, data.fields[6].name);
        $('#recordDetails').append(cal);
        //parseAllFields(data);
        //formatRows();
        loader.fadeOut(200);
    }, function (message) {
        console.log(message);
        //TODO Add errorhandling

    });

    function updateBreadcrumbs(personName, recordName) {
        setTitle(personName, recordName);
        var bc = $('#nav-breadcrumbs');
        var personURL = window.location.origin + "/record/view/" + getEnding(window.location.pathname);
        bc.append("<li>" + personName + "</a></li>");
        bc.append("<li class='active'><a href=\"" + personURL + "\">" + recordName + "</a></li>");
        bc.append(getDoneButton());
        bc.append('<br>');
        bc.append("<h1 style='display: inline'>Editing \"" + recordName + "\"</h1><h6 style='display: inline'>" + personName + "</h6>");
    };

    function getDoneButton(){
        var symbol = $("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>");
        var link = $("<a href=\"" + window.location.origin + "/record/view/" + getEnding(window.location.pathname) + "\" class='btn' />").append(symbol);
        return link;
    }

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

    function getRecordFromServer(recordId) {
        //TODO put the ajax request in here, extract the logic in it into separate functions
    };

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
     /*   "name": "Specialty",
            "type": "String",
            "has_default": false,
            "allow_other": false,
            "values": "Making cheesy comments"*/
        if(field.has_default){
            
        }
/*        field.name = field.name.toLowerCase();
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
        }*/
    }

    function getCalendar(unixtime, fieldName) {
        var realTime = getStringFromEpochTime(unixtime);
        var container = $('<div class="input-group date" data-provide="datepicker" id=\"' + fieldName + 'Picker\">');
        var input = $('<input class="form-control small-table" type="text" placeholder=\"' + fieldName + '\" value=\"' + realTime + '\">');
        container.append(input);
        var cal = $('<div class="input-group-addon small-table" style="height: 14px !important; line-height: 14px !important;">');
        var openIcon = $('<span class="glyphicon glyphicon-th small-table" style="font-size: 12px !important; height: 14px !important; line-height: 14px !important;"></span>');
        cal.append(openIcon);
        container.append(container);
        return container;
    }

    function setTitle(personName, recordName) {
        document.title = "Editing " + personName + ' | ' + recordName;
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

    function getStringFromEpochTime(time) {
        var DateObj = $.parseJSON('{"date_created":"' + time + '"}');
        var myDate = new Date(1000 * DateObj.date_created);
        var dateS = myDate.getMonth() + '/' + myDate.getDay() + '/' + myDate.getFullYear();
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
