///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.8
 *
 */

interface ShortActivityData {
    name:string,
    start_date:string,
    end_date:string,
    id:string
}

interface MenuData {
    error:Error,
    count:number,
    activities:ShortActivityData[]
}

interface ParticipantData {
    given_name:string,
    last_name:string,
    id:string
}

interface DetailActivityData {
    error:Error,
    name:string,
    target_group:string[],
    current_target_group:number,
    target_group_comment:string,
    start_date:string,
    end_date:string,
    participants:ParticipantData[]
}

/**
 * Class to store the token field (the field to add/remove users from a activity)
 * @version 0.0.3
 * TODO get people's names (--> or display more information?) from the database who are not yet in the activity
 */
class ValidatorTokenField {
    private engine:BloodHound;
    private tf; //consider refactoring this
    public load(){
        this.setUp();
    }

    private setUp() {
        this.setSuggestionEngine();
        this.displayTokenField();
        this.preventDuplicates();
    }

    /**
     * Initially sets up the engine of suggestions, stores the state in this.engine
     * @since 0.0.1
     */
    private setSuggestionEngine() {
        //   docs for bloodhound suggestion engine https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md
        this.engine = new Bloodhound({
            local: [{value: 'red'}, {value: 'Tim'}, {value: 'Peter'}, {value: 'Christoph'}],
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
        });
        this.engine.initialize();
    }

    /**
     * Checks if one of the tokens entered is a duplicate of an existing one.
     * @since 0.0.4
     */
    private preventDuplicates(){
        $('#person-input').on('tokenfield:createToken', function(e) {
            console.log('...checking if too many tokens');
            var existingTokens = this.tf('getTokens');
            $.each(existingTokens, function(i, tok) {
                if (tok.value === e.attrs.value){
                    e.preventDefault();
                }});
        });
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

}

/**
 * Defines the menu/table on the left of the view.
 * TODO hook up to API (display first activity)
 * TODO do quick search
 * TODO do the animation of displaying the activity on the right if the user clicks on it
 * @version 0.0.6
 */
class ActivityTable {

    private pagination:JQuery;
    private table:JQuery;
    private search:string;
    private page:number;
    private loader:number;

    /**
     * Loads up all of the information and sets up the instance variables
     */
    public load() {
        this.pagination = $('#pagination');
        this.table = $('#table-body');
        this.search = '';
        this.page = 1;
        this.loader = LoaderManager.createLoader(this.table);
        this.updateTable();
        this.setUp();
    }

    /**
     * Creates the basic structure of the table
     */
    private setUp() {
        var that = this;
        LoaderManager.showLoader((that.loader), function() {
            that.makeAddButton();
            that.setUpPagination();
        var timer = null;
            $('#activities-search').keyup(function () {
                clearTimeout(timer);
                that.search = encodeURIComponent($(this).val());
                timer = setTimeout(function () {
                    that.updateTable();
                }, AJAX_DELAY);
            });
        });
        LoaderManager.hideLoader(that.loader, function () {
            LoaderManager.destroyLoader(that.loader);
        });
    }

    /**
     * Adding the content to the table.
     * @since 0.0.6
     */
    private updateTable(){
        var that = this;
        AJAX.get(Util.url('get/activities/?page=' + that.page + '&search=' + that.search, false), function(data:MenuData) {
                if(data.count < (that.page - 1) * 10) {
                    that.pagination.pagination('selectPage', data.count / 10 - data.count % 10);
                    return;
                }
                that.pagination.pagination('updateItems', data.count);
                that.table.html('');
                if (data.count > 0) {
                    that.addDataToTable(data);
                } else {
                    that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
                }
        }, function (message:string) {
            Util.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }

    /**
     * Creates a new activity specified by the user. Pops up a modal to get name/start/end date and then goes to the view
     */
    private addActivity() {
        //console.log("adding activity...");
    }

    /**
     * Sets up the pagination
     * @since 0.0.4
     */
    private setUpPagination() {
        var that = this;
        this.pagination.pagination({
            items: 0,
            itemsOnPage: 10,
            onPageClick: function (page, event) {
                if(event != null) {
                    event.preventDefault();
                }
                that.page = page;
            }
        });
    }

    /**
     * Links up the button for adding activities with the JS
     */
    private makeAddButton() {
        $('#add-activity').click(this.addActivity);
    }

    /**
     * With the data of all the activities, it successively creates the rows for each activity
     * @param data
     */
    private addDataToTable(data:MenuData) {
        for (var i = 0; i < data.activities.length; i++) {
            var item:ShortActivityData = data.activities[i];
            this.addRowToTable(item);
        }
    }

    /**
     * Successively adds the parameters to one row and adds it to the DOM
     * @param data
     */
    private addRowToTable(data:ShortActivityData) {
        var row:JQuery;
        var startD;
        var endD;
        var that = this;
        startD = Util.formatShortDate(Util.parseSQLDate(<string> data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(<string> data.end_date));
        row = $('<tr></tr>');
        row.append('<td>' + data.name + '</td>');
        row.append('<td>' + startD + ' - ' + endD + '</td>');
        row.click(function() {
            that.displayActivity.call(null, data.id);
        });
        this.table.append(row);
    }

    private displayActivity(activityId:string) {
        window.location.href = window.location.origin + '/activity/view/' + activityId;
    }
}

/**
 * carries out all the tasks related to displaying the actual information of one activity on the right of the view
 * @since 0.0.4
 * TODO: Make the add new person thing work
 * TODO: autosave
 */
class ActivityInformation {

    private peopleTable:JQuery;
    private id:number;

    /**
     * Loads up all of the information and sets up the instance variables
     */
    public load(){
        this.peopleTable = $('#existingPeople');
        this.id = 1;
        this.setUp();
    }

    /**
     * Creates the basic structure of the table
     */
    private setUp(){
        var that = this;
        var loader = LoaderManager.createLoader($('#activityContent'));
        LoaderManager.showLoader((loader), function() {
            AJAX.get(Util.url('get/activity/?id=' + that.id, false), function(data:DetailActivityData) {
                that.displayTitle(data.name);
                that.displayPeople(data.participants);
                that.displayTargetGroup(data.target_group, data.current_target_group);
                that.displayComment(data.target_group_comment);
                that.displayStartDate(data.start_date);
                that.displayEndDate(data.end_date);
            }, function (message:string) {
                Util.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
        LoaderManager.hideLoader(loader, function () {
            LoaderManager.destroyLoader(loader);
        });
    }

    /**
     * Displays the title of the activity in the dedicated textfield
     * @param title
     */
    private displayTitle(title:string){
        $('#activity-title').val(title);
    }

    /**
     * Shows the target group as dropdown
     * @param options
     * @param active
     */
    private displayTargetGroup(options:string[], active:number){
        var dropD = $('#target-dropdown');
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        $('#target-button').append(options[active]);
        for(var i = 0; i < options.length; i++) {
                dropD.append('<li><a>' + options[i] + '</a></li>');
        }
    }

    /**
     * Displays the comment for the target group
     * @param initialData
     */
    private displayComment(initialData:string){
        $('#target-comment').val(initialData);
    }


    /**
     * Displays the start date of the activity
     * @param sqlDate
     */
    private displayStartDate(sqlDate:string){
        var startD:string = Util.formatNumberDate(Util.parseSQLDate(<string> sqlDate));
        var startDate = Util.getDatePicker(startD, "add-start-date");
        $('#start-date').append(startDate);
    }

    /**
     * Displays the end date of the activity
     * @param sqldate
     */
    private displayEndDate(sqldate:string){
        var endD:string = Util.formatNumberDate(Util.parseSQLDate(<string> sqldate));
        var endDate = Util.getDatePicker(endD, "add-start-date");
        $('#end-date').append(endDate);
    }

    /**
     * Creates the table with all the people in a activity
     * @param people
     */
    private displayPeople(people:ParticipantData[]){
        for(var i = 0; i < people.length; i++) {
            var row = $('<td></td>');
            row.append(people[i].given_name + ' ' + people[i].last_name);
            var fullRow = $('<tr></tr>');
            fullRow.append(row);
            this.addOnClickToRow(fullRow, people[i].id);
            this.peopleTable.append(fullRow);
        }
    }

    /**
     * Adds an on-click event to a row in the table in which all the people going to an activity are displayed
     * @param row
     * @param id
     */
    private addOnClickToRow(row:JQuery, id:string) {
        row.click(function() {
            Util.to('record/view/' + id);
        });
    }
}

$(document).ready(function () {
    new ValidatorTokenField().load();
    new ActivityInformation().load();
    new ActivityTable().load();
});

