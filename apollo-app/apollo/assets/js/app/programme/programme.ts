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

var fakeJSON_oneProgramme = <JSON> fakeJSON_obj_oneProgramme;

var fakeJSON_obj_programmeMenu = {
    "error": null,
    "programmes": [
        {
            "name": "Programme 1",
            "start_date": 1457476081,
            "end_date": 1457473081
        },
        {
            "name": "Programme 2",
            "start_date": 1457476081,
            "end_date": 1457473081
        },
        {
            "name": "Programme 3",
            "start_date": 1457476081,
            "end_date": 1457473081
        }
    ]
};

var fakeJSON_programmeMenu = <JSON> fakeJSON_obj_programmeMenu;


interface ParticipantData {
    given_name:string,
    last_name:string,
    id:string
}

interface ShortProgrammeData {
    name:string,
    start_date:number,
    end_date:number,
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
    start_date:number,
    end_date:number,
    participants:ParticipantData[]
}


class ValidatorTokenField {
    private engine:BloodHound;
    private tf:Jquery;
    public load(){
        this.setUp();
    }
    private setUp() {
        this.engine = new Bloodhound({
            local: [{value: 'red'}, {value: 'blue'}, {value: 'green'}, {value: 'yellow'}, {value: 'violet'}, {value: 'brown'}, {value: 'purple'}, {value: 'black'}, {value: 'white'}],
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });
        this.engine.initialize();
        this.tf = $('#person-input').tokenfield({
            typeahead: [null, {source: this.engine.ttAdapter()}]
        });

    }
}

class ProgrammeTable {
    private addProgramme() {
        //console.log("adding programme...");
    }

    private makeAddButton() {
        $('#add-record').click(this.addProgramme);
    }
    public load(){
            this.setUp();
        }

    private setUp() {
            this.makeAddButton();
    }
}

$(document).ready(function () {
    new ValidatorTokenField().load();
    new ProgrammeTable().load();
});

