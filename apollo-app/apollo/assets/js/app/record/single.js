/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */
$(document).ready(function () {
    var personName = "Charlotte Warren-Gash";
    var personId = 531;
    var records = ["PhD student", "President"];
    var recordIds = [13, 15];
    var path = window.location.pathname;
    updateBreadcrumbs(personName, personId, records, recordIds);
    function updateBreadcrumbs(personName, personID, records, recordIds) {
        setTitle(personName, personId);
        var bc = $('#nav-breadcrumbs');
        //bc.html('');
        bc.append("<li>" + personName + "</li>");
        var links = getLinks(records, recordIds, path);
        var dd = getDropdownWithItems(links);
        bc.append(dd);
        bc.append("<h1>" + personName + "</h1>");
    }
    ;
    function setTitle(personName, personId) {
        document.title = "Record #" + personId + " | " + personName;
    }
    ;
    function getOrgName() {
        var orgLi = $('#organisation');
        var orgLiHTML = orgLi.html();
        return orgLiHTML.toString();
    }
    ;
    function getLinks(names, recordItems, path) {
        var url = stripEnding(path);
        var as = [];
        for (var i = 0; i < names.length; i++) {
            as.push("<a href=\"" + url + recordItems[i] + "\">" + names[i] + "</a>");
        }
        return as;
    }
    ;
    function stripEnding(url) {
        var re = new RegExp("/.*\/");
        var base = re.exec(url);
        console.log(base);
        return base[0];
    }
    ;
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
    }
});
/*
 <a href="edit.php" className="btn btn-primary btn-sm pull-right"><span className="glyphicon glyphicon-pencil"
 aria-hidden="true"></span> &nbsp;&nbsp;&nbsp;Edit
 this record</a>
 <ol className="breadcrumb" id="nav-breadcrumbs">
 <li>Apollo</li>
 <li><a href="record.php">Records</a></li>
 <li className="active"><i>Record #531</i> Charlotte Warren-Gash</li>
 </ol>
 </div>
 */
