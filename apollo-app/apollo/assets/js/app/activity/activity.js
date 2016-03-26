///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.7
 *
 */
/**
 * Class to store the token field (the field to add/remove users from a activity)
 * @version 0.0.2
 * TODO get people's names (--> or display more information?) from the database who are not yet in the activity
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
        this.preventDuplicates();
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
     * Checks if one of the tokens entered is a duplicate of an existing one.
     * @since 0.0.4
     */
    ValidatorTokenField.prototype.preventDuplicates = function () {
        $('#person-input').on('tokenfield:createToken', function (e) {
            console.log('...checking if too many tokens');
            var existingTokens = this.tf('getTokens');
            $.each(existingTokens, function (i, tok) {
                if (tok.value === e.attrs.value) {
                    e.preventDefault();
                }
            });
        });
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
 * TODO hook up to API (display first activity)
 * TODO do quick search
 * TODO do the animation of displaying the activity on the right if the user clicks on it
 * @version 0.0.6
 */
var ActivityTable = (function () {
    function ActivityTable() {
    }
    /**
     * Loads up all of the information and sets up the instance variables
     */
    ActivityTable.prototype.load = function () {
        this.pagination = $('#pagination');
        this.table = $('#table-body');
        this.search = '';
        this.page = 1;
        this.loader = LoaderManager.createLoader(this.table);
        this.setUp();
    };
    /**
     * Creates the basic structure of the table
     */
    ActivityTable.prototype.setUp = function () {
        var that = this;
        LoaderManager.showLoader((that.loader), function () {
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
    };
    /**
     * Adding the content to the table.
     * @since 0.0.6
     */
    ActivityTable.prototype.updateTable = function () {
        var that = this;
        AJAX.get(Util.url('get/activities/?page=' + that.page + '&search=' + that.search, false), function (data) {
            if (data.count < (that.page - 1) * 10) {
                that.pagination.pagination('selectPage', data.count / 10 - data.count % 10);
                return;
            }
            that.pagination.pagination('updateItems', data.count);
            that.table.html('');
            if (data.count > 0) {
                that.addDataToTable(data);
            }
            else {
                that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
            }
        }, function (message) {
            Util.error('An error has occurred during the loading of the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    };
    /**
     * Creates a new activity specified by the user. Pops up a modal to get name/start/end date and then goes to the view
     */
    ActivityTable.prototype.addActivity = function () {
        //console.log("adding activity...");
    };
    /**
     * Sets up the pagination
     * @since 0.0.4
     */
    ActivityTable.prototype.setUpPagination = function () {
        var that = this;
        this.pagination.pagination({
            items: 0,
            itemsOnPage: 10,
            onPageClick: function (page, event) {
                if (event != null) {
                    event.preventDefault();
                }
                that.page = page;
            }
        });
    };
    /**
     * Links up the button for adding activities with the JS
     */
    ActivityTable.prototype.makeAddButton = function () {
        $('#add-activity').click(this.addActivity);
    };
    /**
     * With the data of all the activities, it successively creates the rows for each activity
     * @param data
     */
    ActivityTable.prototype.addDataToTable = function (data) {
        for (var i = 0; i < data.activities.length; i++) {
            var item = data.activities[i];
            this.addRowToTable(item);
        }
    };
    /**
     * Successively adds the parameters to one row and adds it to the DOM
     * @param data
     */
    ActivityTable.prototype.addRowToTable = function (data) {
        var row;
        var startD;
        var endD;
        var that = this;
        startD = Util.formatShortDate(Util.parseSQLDate(data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(data.end_date));
        row = $('<tr></tr>');
        row.append('<td>' + data.name + '</td>');
        row.append('<td>' + startD + ' - ' + endD + '</td>');
        row.click(function () {
            that.displayActivity.call(null, data.id);
        });
        this.table.append(row);
    };
    ActivityTable.prototype.displayActivity = function (activityId) {
        window.location.href = window.location.origin + '/activity/view/' + activityId;
    };
    return ActivityTable;
})();
/**
 * carries out all the tasks related to displaying the actual information of one activity on the right of the view
 * @since 0.0.4
 * TODO: Make the add new person thing work
 * TODO: autosave
 */
var ActivityInformation = (function () {
    function ActivityInformation() {
    }
    /**
     * Loads up all of the information and sets up the instance variables
     */
    ActivityInformation.prototype.load = function () {
        this.peopleTable = $('#existingPeople');
        this.id = 1;
        this.setUp();
    };
    /**
     * Creates the basic structure of the table
     */
    ActivityInformation.prototype.setUp = function () {
        var that = this;
        var loader = LoaderManager.createLoader($('#activityContent'));
        LoaderManager.showLoader((loader), function () {
            AJAX.get(Util.url('get/activity/' + that.id, false), function (data) {
                that.displayTitle("Second year placements");
                that.displayPeople(data.participants);
                that.displayTargetGroup(data.target_group, data.current_target_group);
                that.displayComment(data.target_group_comment);
                that.displayStartDate(data.start_date);
                that.displayEndDate(data.end_date);
            }, function (message) {
                Util.error('An error has occurred during the loading of the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
            });
        });
        LoaderManager.hideLoader(loader, function () {
            LoaderManager.destroyLoader(loader);
        });
    };
    /**
     * Displays the title of the activity in the dedicated textfield
     * @param title
     */
    ActivityInformation.prototype.displayTitle = function (title) {
        $('#activity-title').val(title);
    };
    /**
     * Shows the target group as dropdown
     * @param options
     * @param active
     */
    ActivityInformation.prototype.displayTargetGroup = function (options, active) {
        var dropD = $('#target-dropdown');
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        $('#target-button').append(options[active]);
        for (var i = 0; i < options.length; i++) {
            dropD.append('<li><a>' + options[i] + '</a></li>');
        }
    };
    /**
     * Displays the comment for the target group
     * @param initialData
     */
    ActivityInformation.prototype.displayComment = function (initialData) {
        $('#target-comment').val(initialData);
    };
    /**
     * Displays the start date of the activity
     * @param sqlDate
     */
    ActivityInformation.prototype.displayStartDate = function (sqlDate) {
        var startD = Util.formatNumberDate(Util.parseSQLDate(sqlDate));
        var startDate = Util.getDatePicker(startD, "add-start-date");
        $('#start-date').append(startDate);
    };
    /**
     * Displays the end date of the activity
     * @param sqldate
     */
    ActivityInformation.prototype.displayEndDate = function (sqldate) {
        var endD = Util.formatNumberDate(Util.parseSQLDate(sqldate));
        var endDate = Util.getDatePicker(endD, "add-start-date");
        $('#end-date').append(endDate);
    };
    /**
     * Creates the table with all the people in a activity
     * @param people
     */
    ActivityInformation.prototype.displayPeople = function (people) {
        for (var i = 0; i < people.length; i++) {
            var row = $('<td></td>');
            row.append(people[i].given_name + ' ' + people[i].last_name);
            var fullRow = $('<tr></tr>');
            fullRow.append(row);
            this.addOnClickToRow(fullRow, people[i].id);
            this.peopleTable.append(fullRow);
        }
    };
    /**
     * Adds an on-click event to a row in the table in which all the people going to an activity are displayed
     * @param row
     * @param id
     */
    ActivityInformation.prototype.addOnClickToRow = function (row, id) {
        row.click(function () {
            Util.to('record/view/' + id);
        });
    };
    return ActivityInformation;
})();
$(document).ready(function () {
    new ValidatorTokenField().load();
    new ActivityInformation().load();
    new ActivityTable().load();
});
