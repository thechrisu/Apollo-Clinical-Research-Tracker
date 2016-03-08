/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.2
 * /
 */



interface UserData {
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
    users:UserData[]
}

$(document).ready(function() {
    var engine = new Bloodhound({
        local: [{value: 'red'}, {value: 'blue'}, {value: 'green'} , {value: 'yellow'}, {value: 'violet'}, {value: 'brown'}, {value: 'purple'}, {value: 'black'}, {value: 'white'}],
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    engine.initialize();

    $('#person-input').tokenfield({
        typeahead: [null, { source: engine.ttAdapter() }]
    });
});