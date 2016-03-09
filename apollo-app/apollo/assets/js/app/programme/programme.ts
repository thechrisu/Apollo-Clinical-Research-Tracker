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

var fakeJSON_obj_programmeMenu: MenuData  = {
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


interface ParticipantData {
    given_name:string,
    last_name:string,
    id:string
}

interface ShortProgrammeData {
    name:string,
    start_date:string,
    end_date:string,
    id:string
}

interface MenuData {
    error:Error,
    programmes:ShortProgrammeData[]
}
interface DetailProgrammeData {
    error:Error,
    name:string,
    target_group:string[],
    target_group_comment:string,
    programme_funding:string,
    start_date:string,
    end_date:string,
    participants:ParticipantData[]
}

//var fakeJSON_programmeMenu = <JSON> fakeJSON_obj_programmeMenu;


/**
 * Class to store the token field (the field to add/remove users from a program)
 * @version 0.0.1
 */
class ValidatorTokenField {
    private engine:BloodHound;
    private tf; //consider refactoring this
    private elements:ParticipantData[]; //holds the data of the people
    public load(){
        this.setUp();
    }
    private setUp() {
        this.setSuggestionEngine();
        this.displayTokenField();
    }

    /**
     * Initially sets up the engine of suggestions, stores the state in this.engine
     * @since 0.0.1
     */
    private setSuggestionEngine() {
        //   docs for bloodhound suggestion engine https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md
        this.engine = new Bloodhound({
            local: [{value: 'red'}, {value: 'blue'}, {value: 'green'}, {value: 'yellow'}, {value: 'violet'}, {value: 'brown'}, {value: 'purple'}, {value: 'black'}, {value: 'white'}],
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });
        this.engine.initialize();
    }

    /**
     * Displays the token field in the DOM and sets a reference to the object in instance variable tf
     * @since 0.0.1
     */
    private displayTokenField() {
        this.tf = $('#person-input').tokenfield({
            typeahead: [null, {source: this.engine.ttAdapter()}]
        });
    }
    //TODO get people's names (--> or display more information?) from the database who are not yet in the program

}

/**
 * Defines the menu/table on the left of the view.
 * TODO hook up to API (display first programme)
 * TODO do quick search
 * TODO do the animation of displaying the programme on the right if the user clicks on it
 * TODO insert loader
 * @version 0.0.2
 */
class ProgrammeTable {

    /**
     * Loads up all of the information, makes AJAX request for getting the menu
     */
    public load() {
        this.setUp();
    }

    /**
     * Creates the basic structure of the table
     */
    private setUp() {
        this.makeAddButton();
        this.addDataToTable(fakeJSON_obj_programmeMenu);
    }

    /**
     * Creates a new programme specified by the user. Pops up a modal to get name/start/end date and then goes to the view
     */
    private addProgramme() {
        //console.log("adding programme...");
    }

    /**
     * Links up the button for adding programmes with the JS
     */
    private makeAddButton() {
        $('#add-programme').click(this.addProgramme);
    }

    /**
     * With the data of all the programmes, it successively creates the rows for each programme
     * @param data
     */
    private addDataToTable(data:MenuData) {
        for (var i = 0; i < data.programmes.length; i++) {
            var item:ShortProgrammeData = data.programmes[i];
            this.addRowToTable(item);
        }
    }

    /**
     * Successively adds the parameters to one row and adds it to the DOM
     * @param data
     */
    private addRowToTable(data:ShortProgrammeData) {
        var row:JQuery;
        var startD;
        var endD;
        startD = Util.formatShortDate(Util.parseSQLDate(<string> data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(<string> data.end_date));
        row = $('<tr></tr>');
        row.append('<td>' + data.name + '</td>');
        row.append('<td>' + startD + ' - ' + endD + '</td>');
        row.click("test" + data.id);
        $('#table-body').append(row);
    }
}

/**
 * carries out all the tasks related to displaying the actual information of one programme on the right of the view
 * @since 0.0.2
 * TODO: Figure out how to use the ValidatorTokenField on top
 * TODO: Display loader
 * TODO: autosave
 */
class ProgrammeInformation {
    private displayTitle(title:string){
        $('#programme-title').html(title);
    }

    private displayTargetGroup(){
        //TODO
        $('#funding').html("<select id='target-dropdown' />");
    }

    private displayComment(){
        //TODO
    }

    private displayFunding(text:string){
        $('#funding').html(text);
    }

    private displayPeople(people:){
        //TODO
    }

    private displayDates(sqlDate:string{
        //TODO insert datepicker item, correctly parse date
        var sDate_f:string = Util.formatDate(Util.parseSQLDate(<string> sqlDate));
        $('#start-date').html("<span>Insert datepicker here</span>");
    }

    private displayEndDate(sqldate:string){
        //TODO insert datepicker item, correctly parse date
        var endD:string = Util.formatDate(Util.parseSQLDate(<string> sqldate));
        $('#end-date').html("<span>Insert datepicker here</span>");
    }
}

$(document).ready(function () {
    new ValidatorTokenField().load();
    new ProgrammeTable().load();
});

