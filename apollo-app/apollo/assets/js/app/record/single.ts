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
            "values": "M"
        },
        {
            "name": "Cars",
            "type": "StringAr",
            "values": "Aston Martin DB5"
        },
        {
            "name": "Funding Source",
            "type": "String",
            "values": "M"
        },
        {
            "name": "Payband",
            "type": "String",
            "values": "7"
        },
        {
            "name": "References",
            "type": "Text",
            "values": "Mister Bond is one of our nicest employees. In fact, he even developed new applications in conjunction with Q. He is always punctual"
        },
        {
            "name": "Specialty",
            "type": "String",
            "values": "Making cheesy comments"
        },
        {
            "name": "Start Date",
            "type": "Date",
            "values": 1456940959
        },
        {
            "name": "End Date",
            "type": "Date",
            "values": 1456941022
        }
    ]
};

JSON: fakeJSON = <JSON>fakeJSON_obj;


$(document).ready(function () {
    var path = window.location.pathname;
    var recordNumber = getEnding(path);
    getRecordFromServer(recordNumber);
    fakeajaxGet(fakeJSON, function (data) {
        console.log("Success");
        var fullName = data.essential.given_name + ' ' + data.essential.last_name;
        updateBreadcrumbs(fullName, data.essential.record_name, data.essential.record_names, data.essential.record_ids);
        displayEssentialInfo(data.essential);
      /*  to parse

        <div class="col-md-4">
            <h3>
                <small>Address:</small>
        </h3>
        <h3>Institute of Child Health</h3>
        <h3>UCL, Gower Street</h3>
        <h3>WC1E 6BT, London, UK</h3>
        </div>
        </div>

        <div class="row top-buffer">

        <div class="col-md-4">

        <div class="table-responsibe">

        <table class="table">

            <caption>Funding</caption>

            <tbody>

                <tr>
                    <td>Funding Source:</td>
        <td>Deanery</td>
        </tr>

        <tr>
            <td>Funding Category:</td>
        <td>Deanery</td>
        </tr>

        <tr>
            <td>Pay Band:</td>
        <td>9</td>
        </tr>

        </tbody>

        </table>

        </div>

        <div class="table-responsibe">

        <table class="table">

            <caption>Speciality</caption>

            <tbody>

                <tr>
                    <td>Clinical Speciality:</td>
        <td>Public Health Medicine</td>
        </tr>

        <tr>
            <td>HRCS Health Category:</td>
        <td>Population Health</td>
        </tr>

        </tbody>

        </table>

        </div>

        </div>

        <div class="col-md-4">

        <div class="table-responsibe">

        <table class="table">

            <caption>Research Activity</caption>

        <tbody>

            <tr>
                <td>HRCS Research Activity Codes:</td>
        <td>Epidemiology</td>
        </tr>

        <tr>
            <td>Research Area:</td>
        <td>Epidemiology</td>
        </tr>

        <tr>
            <td>Start Date:</td>
        <td>April 1st, 2014</td>
        </tr>

        <tr>
            <td>End Date:</td>
        <td>March 31st, 2018</td>
        </tr>

        <tr>
            <td>Supervisor:</td>
        <td>Professor Geraint Rees</td>
        </tr>

        </tbody>

        </table>

        </div>

        </div>

        <div class="col-md-4">

        <div class="table-responsibe">

        <table class="table">

            <caption>Other Info</caption>

        <tbody>

            <tr>
                <td>NHS Trust</td>
        <td>GOSH</td>
        </tr>

        <tr>
            <td>Next Destination:</td>
        <td class="secondary-text">Unknown</td>
            </tr>

            <tr>
                <td>Previous Post:</td>
        <td>Clinical Research Training Fellow</td>
        </tr>

        <tr>
            <td>Highest Degree Type:</td>
        <td>PhD</td>
        </tr>

        <tr>
            <td>PhD Title:</td>
        <td>Some PhD Title</td>
        </tr>

        </tbody>

        </table>

        </div>

        </div>

        </div>

        <div class="row top-buffer">

        <div class="col-md-4">
        <div class="table-responsive">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Publications</th>
        </tr>
        </thead>
        <tbody id="table-body-1">
        </tbody>
        </table>
        </div>
        </div>

        <div class="col-md-4">
        <div class="table-responsive">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Awards</th>
        </tr>
        </thead>
        <tbody id="table-body-2">
        </tbody>
        </table>
        </div>
        </div>

        <div class="col-md-4">
        <div class="table-responsive">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Programmes</th>
        </tr>
        </thead>
        <tbody id="table-body-3">
            </tbody>
            </table>
            </div>
            </div>

            </div>

            </div>

            </div>    ";    */
    }, function (message) {
        console.log(message);
        //TODO Add errorhandling

    });

    function updateBreadcrumbs(personName, recordName, recordNames, recordIds) {
        setTitle(personName, recordName);
        var bc = $('#nav-breadcrumbs');
        //bc.html('');
        bc.append("<li>" + personName + "</li>");
        var links = getLinks(recordNames, recordIds, path);
        var dd = getDropdownWithItems(links);
        bc.append(dd);
        bc.append('<br>');
        bc.append("<h1 style='display: inline'>" + recordName + "</h1><h6 style='display: inline'>" + personName + "</h6>");
    };

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

    function displayEssentialInfo(data){
        var g = $('#recordGeneric');
        g.append("<div className='col-md-4'><h3>" + data.given_name + ' ' + data.last_name + "</h3></div><small>Name</small>");
        g.append("<div className='col-md-3'><h3>" + data.email + "</h3></div><small>email</small>");
        g.append("<div className='col-md-3'><h3>" + data.phone + "</h3></div><small>phone</small>");
        g.append("<div className='col-md-2'><h3>" + data.record_name + "</h3></div><small>record name</small>");
    }

    function setTitle(personName, recordName) {
        document.title = personName + ' | ' + recordName;
    };

    function getLinks(names, recordItems, path) {
        var url = stripEnding(path);
        var as = [];
        for (var i = 0; i < names.length; i++) {
            as.push("<a href=\"" + url + recordItems[i] + "\">" + names[i] + "</a>");
        }
        return as;
    };

    function getEnding(url) {
        var re = new RegExp("[^\/]+(?=\/*$)|$"); //Matches anything that comes after the last slash (and anything before final slashes)
        var base = re.exec(url);
        console.log(base);
        if (base == null) {
            console.error("URL ending could not be found out correctly, URL: " + url);
        } else {
            return base[0];
        }
    };

    function stripEnding(url) {
        var re = new RegExp("/.*\/");
        var base = re.exec(url);
        console.log(base);
        if (base == null) {
            console.error("URL ending could not be found out correctly, URL: " + url);
        } else {
            return base[0];
        }
    }


});

