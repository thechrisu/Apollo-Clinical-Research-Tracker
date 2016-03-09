///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.2
 * /
 */
var fakeJSON_obj_oneProgramme = {
    "error": null,
    "name": "Some programme",
    "target_group": ["Young people", "Old people", "Twentysomething people"],
    "target_group_comment": "This is an exceptional programme",
    "programme_funding": "Through funds",
    "start_date": 1457476303,
    "end_date": 1857476303,
    "participants": [
        {
            "given_name": "Peter",
            "last_name": "Parker",
            "id": "13"
        },
        {
            "given_name": "Michael",
            "last_name": "Jackdaughter",
            "id": "12"
        },
        {
            "given_name": "Rowan",
            "last_name": "@kinson",
            "id": "1"
        }
    ]
};
//var fakeJSON_oneProgramme = <JSON> fakeJSON_obj_oneProgramme;
var fakeJSON_obj_programmeMenu = {
    "error": null,
    "programmes": [
        {
            "name": "Programme 1",
            "start_date": "2008-11-11",
            "end_date": "2008-11-11",
            "id": "1"
        },
        {
            "name": "Programme 2",
            "start_date": "2008-11-11",
            "end_date": "2008-11-11",
            "id": "1"
        },
        {
            "name": "Programme 1",
            "start_date": "2008-11-11",
            "end_date": "2008-11-11",
            "id": "1"
        }
    ]
};
//var fakeJSON_programmeMenu = <JSON> fakeJSON_obj_programmeMenu;
/**
 * Class to store the token field (the field to add/remove users from a program)
 * @version 0.0.1
 */
var ValidatorTokenField = (function () {
    function ValidatorTokenField() {
    }
    ValidatorTokenField.prototype.load = function () {
        this.setUp();
    };
    ValidatorTokenField.prototype.setUp = function () {
        this.setSuggestionEngine();
        this.displayTokenField();
    };
    /**
     * Initially sets up the engine of suggestions, stores the state in this.engine
     * @since 0.0.1
     */
    ValidatorTokenField.prototype.setSuggestionEngine = function () {
        //   docs for bloodhound suggestion engine https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md
        this.engine = new Bloodhound({
            local: [{ value: 'red' }, { value: 'blue' }, { value: 'green' }, { value: 'yellow' }, { value: 'violet' }, { value: 'brown' }, { value: 'purple' }, { value: 'black' }, { value: 'white' }],
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });
        this.engine.initialize();
    };
    /**
     * Displays the token field in the DOM and sets a reference to the object in instance variable tf
     * @since 0.0.1
     */
    ValidatorTokenField.prototype.displayTokenField = function () {
        this.tf = $('#person-input').tokenfield({
            typeahead: [null, { source: this.engine.ttAdapter() }]
        });
    };
    return ValidatorTokenField;
})();
/**
 * Defines the menu/table on the left of the view.
 * TODO hook up to API (display first programme)
 * TODO do quick search
 * TODO do the animation of displaying the programme on the right if the user clicks on it
 * TODO insert loader
 * @version 0.0.2
 */
var ProgrammeTable = (function () {
    function ProgrammeTable() {
    }
    /**
     * Loads up all of the information, makes AJAX request for getting the menu
     */
    ProgrammeTable.prototype.load = function () {
        this.setUp();
    };
    /**
     * Creates the basic structure of the table
     */
    ProgrammeTable.prototype.setUp = function () {
        this.makeAddButton();
        this.addDataToTable(fakeJSON_obj_programmeMenu);
    };
    /**
     * Creates a new programme specified by the user. Pops up a modal to get name/start/end date and then goes to the view
     */
    ProgrammeTable.prototype.addProgramme = function () {
        //console.log("adding programme...");
    };
    /**
     * Links up the button for adding programmes with the JS
     */
    ProgrammeTable.prototype.makeAddButton = function () {
        $('#add-programme').click(this.addProgramme);
    };
    /**
     * With the data of all the programmes, it successively creates the rows for each programme
     * @param data
     */
    ProgrammeTable.prototype.addDataToTable = function (data) {
        for (var i = 0; i < data.programmes.length; i++) {
            var item = data.programmes[i];
            this.addRowToTable(item);
        }
    };
    /**
     * Successively adds the parameters to one row and adds it to the DOM
     * @param data
     */
    ProgrammeTable.prototype.addRowToTable = function (data) {
        var row;
        var startD;
        var endD;
        startD = Util.formatDate(Util.parseSQLDate(data.start_date));
        endD = Util.formatDate(Util.parseSQLDate(data.end_date));
        row = $('<tr></tr>');
        row.append('<td>' + data.name + '</td>');
        row.append('<td>' + startD + '</td>');
        row.append('<td>' + endD + '</td>');
        row.click("test" + data.id);
        $('#table-body').append(row);
    };
    return ProgrammeTable;
})();
/**
 * carries out all the tasks related to displaying the actual information of one programme on the right of the view
 * @since 0.0.2
 * TODO: Figure out how to use the ValidatorTokenField on top
 * TODO: Display loader
 * TODO: autosave
 */
var ProgrammeInformation = (function () {
    function ProgrammeInformation() {
    }
    ProgrammeInformation.prototype.displayTitle = function (title) {
        $('#programme-title').html(title);
    };
    ProgrammeInformation.prototype.displayTargetGroup = function () {
        //TODO
        $('#funding').html("<select id='target-dropdown' />");
    };
    ProgrammeInformation.prototype.displayTargetComment = function () {
        //TODO
    };
    ProgrammeInformation.prototype.displayFunding = function (text) {
        $('#funding').html(text);
    };
    ProgrammeInformation.prototype.displayPeople = function (people) {
        //TODO
    };
    ProgrammeInformation.prototype.displayStartDate = function (sqldate) {
        //TODO insert datepicker item, correctly parse date
        var sDate = Util.formatDate(Util.parseSQLDate(sqldate));
        $('#start-date').html("<span>Insert datepicker here</span>");
    };
    ProgrammeInformation.prototype.displayEndDate = function (sqldate) {
        //TODO insert datepicker item, correctly parse date
        var endD = Util.formatDate(Util.parseSQLDate(sqldate));
        $('#end-date').html("<span>Insert datepicker here</span>");
    };
    return ProgrammeInformation;
})();
$(document).ready(function () {
    new ValidatorTokenField().load();
    new ProgrammeTable().load();
});
