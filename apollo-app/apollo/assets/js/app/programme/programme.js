///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.3
 *
 */
var fakeJSON_obj_oneProgramme = {
    "error": null,
    "name": "Some programme",
    "target_group": ["Young people", "Old people", "Twentysomething people"],
    "current_target_group": 0,
    "target_group_comment": "This is an exceptional programme",
    "programme_funding": "Through funds",
    "start_date": "1834-02-22 02:00:00",
    "end_date": "1834-02-22 02:00:00",
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
            "start_date": "1834-02-22 02:00:00",
            "end_date": "1834-02-22 02:00:00",
            "id": "1"
        },
        {
            "name": "Programme 2",
            "start_date": "1834-02-22 02:00:00",
            "end_date": "1834-02-22 02:00:00",
            "id": "1"
        },
        {
            "name": "Programme 1",
            "start_date": "1834-02-22 02:00:00",
            "end_date": "1834-02-22 02:00:00",
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
            local: [{ value: 'red' }, { value: 'Tim' }, { value: 'Peter' }, { value: 'Christoph' }],
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
 * TODO: Animation for when going to a programme
 * @version 0.0.3
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
        startD = Util.formatShortDate(Util.parseSQLDate(data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(data.end_date));
        row = $('<tr></tr>');
        row.append('<td>' + data.name + '</td>');
        row.append('<td>' + startD + ' - ' + endD + '</td>');
        row.click(this.displayProgramme);
        $('#table-body').append(row);
    };
    ProgrammeTable.prototype.displayProgramme = function (programmeId) {
        window.location.href = window.location.origin + '/programme/' + programmeId;
    };
    return ProgrammeTable;
})();
/**
 * carries out all the tasks related to displaying the actual information of one programme on the right of the view
 * @since 0.0.2
 * TODO: Make the add new person thing work
 * TODO: Display loader
 * TODO: autosave
 */
var ProgrammeInformation = (function () {
    function ProgrammeInformation() {
    }
    ProgrammeInformation.prototype.load = function () {
        this.displayTitle("Second year placements");
        this.displayPeople(fakeJSON_obj_oneProgramme.participants);
        this.displayTargetGroup(fakeJSON_obj_oneProgramme.target_group, fakeJSON_obj_oneProgramme.current_target_group);
        this.displayComment(fakeJSON_obj_oneProgramme.target_group_comment);
        this.displayStartDate(fakeJSON_obj_oneProgramme.start_date);
        this.displayEndDate(fakeJSON_obj_oneProgramme.end_date);
    };
    ProgrammeInformation.prototype.displayTitle = function (title) {
        $('#programme-title').val(title);
    };
    ProgrammeInformation.prototype.displayTargetGroup = function (options, active) {
        var dropD = $('#target-dropdown');
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        $('#target-button').append(options[active]);
        for (var i = 0; i < options.length; i++) {
            dropD.append('<li><a>' + options[i] + '</a></li>');
        }
    };
    ProgrammeInformation.prototype.displayComment = function (initialData) {
        $('#target-comment').val(initialData);
    };
    ProgrammeInformation.prototype.displayFunding = function (text) {
        $('#funding').html(text);
    };
    ProgrammeInformation.prototype.displayPeople = function (people) {
        var table = $('#existingPeople');
        for (var i = 0; i < people.length; i++) {
            var row = $('<td></td>');
            row.append(people[i].given_name + ' ' + people[i].last_name);
            var fullRow = $('<tr href="#"></tr>');
            fullRow.append(row);
            fullRow.click(window.location.href = window.location.origin + '/record/view/' + people[i].id);
            table.append(fullRow);
        }
    };
    ProgrammeInformation.prototype.displayStartDate = function (sqlDate) {
        //TODO insert datepicker item
        var startD = Util.formatDate(Util.parseSQLDate(sqlDate));
        $('#start-date').html("<span>Insert startdate  here</span>");
    };
    ProgrammeInformation.prototype.displayEndDate = function (sqldate) {
        //TODO insert datepicker item
        var endD = Util.formatDate(Util.parseSQLDate(sqldate));
        $('#end-date').html("<span>Insert startdate here</span>");
    };
    return ProgrammeInformation;
})();
$(document).ready(function () {
    //  new ValidatorTokenField().load();
    new ProgrammeInformation().load();
    new ProgrammeTable().load();
});
