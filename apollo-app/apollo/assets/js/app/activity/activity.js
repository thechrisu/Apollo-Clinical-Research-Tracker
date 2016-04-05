///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
///<reference path="../inputs.ts"/>
///<reference path="../bootbox.d.ts"/>
/**
 * Class to store the token field (the field to add/remove users from a activity)
 * @version 0.0.6
 * TODO Dynamically update the table when new entries were added. Add "autosave"
 */
var PeopleField = (function () {
    function PeopleField() {
        this.temporarily_added = []; //people that are temporarily added to the activity (-> not saved). These should not be suggested.
        this.temporarily_removed = []; //people that are temporarily added to the suggestions. These items were removed from the activity
    }
    PeopleField.prototype.load = function (activity_id) {
        this.activity_id = activity_id;
        this.search = '';
        this.temporarily_added = [];
        this.temporarily_removed = [];
        this.setUp();
    };
    PeopleField.prototype.setUp = function () {
        var that = this;
        this.resetBloodhound();
        $('#person-input').keyup(function () {
            that.search = encodeURIComponent($(this).val());
        });
        /*$('#person-input').keydown(function(e) {
            that.search = encodeURIComponent($(this).val());
        }*/
    };
    PeopleField.prototype.resetBloodhound = function () {
        var that = this;
        this.setBloodhound(that);
        var promise = this.bh.initialize();
        promise.fail(function () { Util.error('failed to load the suggestion engine'); });
        this.resetTypeahead();
    };
    PeopleField.prototype.resetTypeahead = function () {
        var that = this;
        $('#person-input').val("");
        $('#person-input').typeahead('destroy');
        $('#person-input').typeahead({
            highlight: true,
            skipCache: true
        }, {
            name: 'data',
            displayKey: 'name',
            source: that.bh.ttAdapter(),
            templates: {
                suggestion: function (data) {
                    var str = '';
                    if (!Util.isIn(data, that.temporarily_added))
                        str += '<div class="noselect">' + data.name + '</div>';
                    return str;
                }
            }
        });
    };
    //http://stackoverflow.com/questions/25419972/twitter-typeahead-add-custom-data-to-dataset
    PeopleField.prototype.setBloodhound = function () {
        var that = this;
        this.bh = new Bloodhound({
            datumTokenizer: function (a) {
                return Bloodhound.tokenizers.whitespace(a.name);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function (item) { return item.id; },
            sorter: sortParticipants,
            remote: {
                url: Util.url('get/activitypeople') + '?activity_id=' + that.activity_id + that.formatTemporarily_added(that.temporarily_added) + '&search=' + that.search,
                filter: function (data) {
                    if (!data) {
                        return {};
                    }
                    else {
                        function destringify(data) {
                            $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    name: item.name
                                };
                            });
                        }
                        function carryout(data) {
                            var objects = destringify(data);
                            var output = [];
                            $.each(objects, function (k, v) {
                                if (!Util.isIn(v, that.temporarily_added) && !Util.isIn(v, that.temporarily_removed)) {
                                    output.push(v);
                                }
                            });
                            $.each(that.temporarily_removed, function (k, v) {
                                output.push(v);
                            });
                            return output;
                        }
                        return carryout(data.data);
                    }
                },
                rateLimitWait: 300
            }
        });
    };
    PeopleField.prototype.removeItemFromSuggestions = function (data) {
        this.temporarily_added.push(data);
        if (Util.isIn(data, this.temporarily_removed))
            Util.removeFromArray(data, this.temporarily_removed);
    };
    PeopleField.prototype.addItemToSuggestions = function (data) {
        this.temporarily_removed.push(data);
        if (Util.isIn(data, this.temporarily_added))
            Util.removeFromArray(data, this.temporarily_added);
        this.bh.add(data);
    };
    PeopleField.prototype.addDataToSuggestions = function (data) {
        $.each(data, function (key, obj) {
            this.addItemToSuggestions(obj);
        });
    };
    PeopleField.prototype.formatTemporarily_added = function (tA) {
        var query = '';
        for (var id in tA) {
            query += '&temporarily_added[]=' + id;
        }
        return query;
    };
    PeopleField.prototype.setId = function (id) {
        this.activity_id = id;
    };
    return PeopleField;
})();
/**
 * Defines the menu/table on the left of the view.
 * @version 0.0.6
 */
var ActivityTable = (function () {
    function ActivityTable() {
    }
    /**
     * Loads up all of the information and sets up the instance variables
     */
    ActivityTable.prototype.load = function (content) {
        this.loader = LoaderManager.createLoader($('#table-body'));
        var that = this;
        LoaderManager.showLoader((this.loader), function () {
            that.content = content;
            that.pagination = $('#pagination');
            that.table = $('#table-body');
            that.search = '';
            that.page = 1;
            that.updateTable();
            that.setUp();
        });
        LoaderManager.hideLoader(this.loader, function () {
            LoaderManager.destroyLoader(that.loader);
        });
    };
    /**
     * Creates the basic structure of the table
     */
    ActivityTable.prototype.setUp = function () {
        this.setUpButtons();
        this.setUpPagination();
        var timer = null;
        var that = this;
        $('#activities-search').keyup(function () {
            clearTimeout(timer);
            that.search = encodeURIComponent($(this).val());
            timer = setTimeout(function () {
                that.updateTable();
            }, AJAX_DELAY);
        });
        this.activateButtons();
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
            Util.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    };
    /**
     * Creates a new activity specified by the user. Pops up a modal to get name/start/end date and then goes to the view
     */
    ActivityTable.prototype.addActivity = function (e) {
        e.preventDefault();
        bootbox.dialog({
            title: 'Adding a new activity',
            message: $('#add-modal').html(),
            buttons: {
                main: {
                    label: "Cancel",
                    className: "btn-primary",
                    callback: function () {
                    }
                },
                success: {
                    label: "Add",
                    className: "btn-success",
                    callback: function () {
                        var modal = $('.modal');
                        var name = modal.find('#add-name').val();
                        var startDate = Util.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                        var endDate = Util.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
                        newActivity(name, startDate, endDate);
                    }
                }
            }
        });
        function newActivity(name, startDate, endDate) {
            AJAX.post(Util.url('post/activity'), {
                action: 'create',
                activity_name: name,
                start_date: startDate,
                end_date: endDate
            }, function (response) {
                Util.to('activity/view/' + response.activity_id);
            }, function (message) {
                Util.error('An error has occurred during the process of creation of the activity. Error message: ' + message);
            });
        }
    };
    ActivityTable.prototype.hideActivity = function (id) {
        bootbox.confirm('Are you sure you want to hide this activity? The data won\'t be deleted and can be restored later.', function (result) {
            if (result) {
                AJAX.post(Util.url('post/activity'), {
                    action: 'hide',
                    activity_id: id
                }, function (response) {
                    Util.to('activity/');
                }, function (message) {
                    Util.error('An error has occurred while hiding activity. Error message: ' + message);
                });
            }
        });
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
    ActivityTable.prototype.setUpButtons = function () {
        var that = this;
        var active = this.content.getId();
        $('#add-activity').click(this.addActivity);
        $('#hide-activity').click(function () {
            that.hideActivity.call(null, active);
        });
    };
    ActivityTable.prototype.activateButtons = function () {
        var addButton = $("#add-activity");
        addButton.removeClass('disabled');
        var duplicateButton = $("#duplicate-activity");
        duplicateButton.removeClass('disabled');
        var hideButton = $("#hide-activity");
        hideButton.removeClass('disabled');
        var targetGroupButton = $("#target-button");
        targetGroupButton.removeClass('disabled');
    };
    /**
     * With the data of all the activities, it successively creates the rows for each activity
     * @param data
     */
    ActivityTable.prototype.addDataToTable = function (data) {
        var activeId = this.content.getId();
        if (isNaN(activeId)) {
            activeId = parseInt(data.activities[0].id);
        }
        for (var i = 0; i < data.activities.length; i++) {
            var item = data.activities[i];
            this.addRowToTable(item, parseInt(item.id) == activeId);
        }
    };
    /**
     * Successively adds the parameters to one row and adds it to the DOM
     * @param data
     */
    ActivityTable.prototype.addRowToTable = function (data, active) {
        var row;
        var startD;
        var endD;
        var that = this;
        startD = Util.formatShortDate(Util.parseSQLDate(data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(data.end_date));
        row = $('<tr></tr>');
        if (active) {
            row.addClass('active');
        }
        row.append('<td>' + data.name + '</td>');
        row.append('<td>' + startD + ' - ' + endD + '</td>');
        row.click(function () {
            that.displayActivity.call(null, data.id);
        });
        row.addClass('noselect');
        this.table.append(row);
    };
    ActivityTable.prototype.displayActivity = function (activityId) {
        Util.to('/activity/view/' + activityId);
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
        this.addedPeople = [];
        this.removedPeople = [];
    }
    ActivityInformation.prototype.getId = function () {
        return this.id;
    };
    /**
     * Loads up all of the information and sets up the instance variables
     */
    ActivityInformation.prototype.load = function (id, existingPeople) {
        var loader = LoaderManager.createLoader($('#activityContent'));
        var that = this;
        LoaderManager.showLoader((loader), function () {
            that.existingPeople = existingPeople;
            that.peopleTable = $('#existingPeople');
            that.id = id;
            that.activeTargetGroup = NaN;
            that.setUp();
            that.existingPeople.setId(id);
            that.existingPeople.load(id);
            that.makeLinkWithSuggestions();
        });
        LoaderManager.hideLoader(loader, function () {
            LoaderManager.destroyLoader(loader);
        });
    };
    /**
     * Creates the basic structure of the table
     */
    ActivityInformation.prototype.setUp = function () {
        var that = this;
        this.removedPeople = [];
        AJAX.get(Util.url('get/activity/?id=' + that.id, false), function (data) {
            var breadcrumbs = $('#nav-breadcrumbs');
            breadcrumbs.find('li:nth-child(3)').text('Activity #' + that.id + ': ' + data.name);
            that.activeTargetGroup = data.current_target_group;
            that.people = data.participants;
            that.displayTitle(data.name);
            that.displayPeople();
            that.displayTargetGroup(data.target_group);
            that.displayComment(data.target_group_comment);
            that.displayStartDate(data.start_date);
            that.displayEndDate(data.end_date);
        }, function (message) {
            Util.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    };
    /**
     * Displays the title of the activity in the dedicated textfield
     * @param title
     */
    ActivityInformation.prototype.displayTitle = function (title) {
        $('#activity-title').val(title);
    };
    ActivityInformation.prototype.makeLinkWithSuggestions = function () {
        $('.twitter-typeahead').on('typeahead:selected', { that: this }, addItemFromSuggestion);
    };
    /**
     * Shows the target group as dropdown
     * @param options
     * @param active
     */
    ActivityInformation.prototype.displayTargetGroup = function (options) {
        var dropD = $('#target-dropdown');
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        var bt = $('#target-button');
        bt.append(options[this.activeTargetGroup] + ' <span class="caret"></span>');
        for (var i = 0; i < options.length; i++) {
            var option = $('<li optionNameUnique="' + i + '"><a>' + options[i] + '</a></li>');
            var that = this;
            if (i == this.activeTargetGroup) {
                option.addClass('disabled');
            }
            else {
                option.click(function () {
                    that.activeTargetGroup = parseInt($(this).attr('optionNameUnique'));
                    dropD.empty();
                    bt.empty();
                    that.displayTargetGroup(options);
                });
                option.addClass('noselect');
            }
            dropD.append(option);
        }
    };
    /*private resetTimer(){
        $.idleTimer(600);
        var that = this;
        $(document).bind("idle.idleTimer", that.save);
        $(document).bind("active.idleTimer", that.resetTimer);
    }

    private save(){
        console.log("saving...");
        $.idleTimer('destroy');
        this.resetTimer();
    }*/
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
        var startDate = Util.getDatePicker(startD, "start-date-picker");
        $('#start-date').append(startDate);
    };
    /**
     * Displays the end date of the activity
     * @param sqldate
     */
    ActivityInformation.prototype.displayEndDate = function (sqldate) {
        var endD = Util.formatNumberDate(Util.parseSQLDate(sqldate));
        var endDate = Util.getDatePicker(endD, "start-date-picker");
        $('#end-date').append(endDate);
    };
    /**
     * Creates the table with all the people in a activity
     * @param people
     */
    ActivityInformation.prototype.displayPeople = function () {
        var people = this.people.concat(this.addedPeople);
        this.peopleTable.empty();
        var that = this;
        people.sort(sortParticipants);
        for (var i = 0; i < people.length; i++) {
            var person = people[i];
            this.displayPerson(person);
        }
    };
    ActivityInformation.prototype.displayPerson = function (person) {
        var that = this;
        if (!Util.isIn(person, this.removedPeople)) {
            var row = $('<td class="col-md-11"></td>');
            row.append(person.name);
            var removeButton = $('<td class="col-md-1"><button type="button" class="btn btn-xs btn-default" style="display:block; text-align:center"><small><span class="glyphicon glyphicon-remove" aria-hidden="false"></span></small></button></td>');
            that.addRemoveClick(removeButton, person);
            var fullRow = $('<tr class="row"></tr>');
            fullRow.append(row);
            fullRow.append(removeButton);
            that.addOnClickToRow(row, person.id);
            that.peopleTable.append(fullRow);
        }
    };
    ActivityInformation.prototype.addRemoveClick = function (button, person) {
        var that = this;
        function removePerson(e) {
            var c = e.data; //context
            Util.removeFromArray(c.person, c.that.addedPeople);
            c.that.removedPeople.push(c.person);
            c.that.existingPeople.addItemToSuggestions(c.person);
            //that.removePerson.call(null, person.id);
            c.that.displayPeople();
        }
        button.click({ person: person, that: that }, removePerson);
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
function sortParticipants(a, b) {
    if (a.name > b.name)
        return 1;
    if (b.name > a.name)
        return -1;
    return 0;
}
function addItemFromSuggestion(e, item) {
    var c = e.data;
    if (Util.isIn(item, c.that.removedPeople)) {
        Util.removeFromArray(item, c.that.removedPeople);
    }
    else {
        c.that.addedPeople.push(item);
    }
    c.that.existingPeople.removeItemFromSuggestions(item);
    c.that.displayPeople();
    c.that.existingPeople.resetBloodhound();
    c.that.makeLinkWithSuggestions();
}
$(document).ready(function () {
    var id = Util.extractId(window.location.toString());
    if (isNaN(id)) {
        var breadcrumbs = $('#nav-breadcrumbs');
        var fullLink = breadcrumbs.find('li:nth-child(2)').find("a").attr("href");
        var newId = Util.extractId(fullLink);
        id = newId;
    }
    var activity = new ActivityInformation();
    var existingPeopleField = new PeopleField();
    activity.load(id, existingPeopleField);
    var menu = new ActivityTable().load(activity);
});
