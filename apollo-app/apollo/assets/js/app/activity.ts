///<reference path="ajax.ts"/>
///<reference path="scripts.ts"/>
///<reference path="../typings/jquery.d.ts"/>
///<reference path="inputs.ts"/>
///<reference path="../typings/bootbox.d.ts"/>
///<reference path="../typings/typeahead.d.ts"/>
///<reference path="columns.ts"/>
///<reference path="apollopagination.ts"/>
const PAGINATION_ITEMS_DEFAULT_ID = 'paginationNumItems';
const PAGINATION_ITEMS_ACTIVITYPARTICIPANTS_ID = "paginationNumItemsActivityParticipants";
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.2.0
 * @todo for some later time: Make use of WebUtil.buildNode to replace all the jQuery constructors
 *
 */

interface MenuData {
    error:Error,
    count:number,
    activities:ShortActivityData[]
}

interface ParticipantData {
    name:string,
    p_id:string,
    r_id:string
}
/*
interface TargetGroupData {
    name:string,
    id:string
}

interface TargetGroupObject {
    active:TargetGroupData,
    data:TargetGroupData[]
}*/

interface DetailActivityData {
    error:Error,
    page:number,
    name:string,
    //target_groups:TargetGroupObject,
    target_group_comment:string,
    start_date:string,
    end_date:string,
    participants:ParticipantData[]
}

var ac_id:number = NaN;
/**
 * Class to store the token field (the field to add/remove users from a activity)
 * @todo Make this more general: Make a sort-of wrapper for typescript with the added/removed arrays
 * Note that at the moment, there is the problem of typeahead not being updated for typescript, as it seems.
 * This means we have to keep seeing ugly errors in our IDE.
 * Also see
 * @link http://stackoverflow.com/questions/32395563/twitter-typeahead-bloodhound-error-in-typescript
 * @version 0.1.0
 */
class PeopleField {
    private search:string;
    private bh:Bloodhound; //this is unfixable (
    private people:ParticipantData[] = [];
    private temporarily_added:ParticipantData[] = []; //people that are temporarily added to the activity (-> not saved). These should not be suggested.
    private temporarily_removed:ParticipantData[] = []; //people that are temporarily added to the suggestions. These items were removed from the activity

    /**
     * Initialises the object to the required state
     * @param activity_id
     */
    public load() {
        this.search = '';
        this.resetAdded();
        this.resetBloodhound();
    }

    /**
     * Just saves the current state of suggestions and then sets up bloodhound again
     */
    public resetBloodhound() {
        for (var i = 0; i < this.temporarily_added.length; i++) {
            ArrayUtil.removeFromArrayCmp(this.temporarily_added[i], this.people, cmpPIds);
        }
        for (var i = 0; i < this.temporarily_added.length; i++) {
            var item = this.temporarily_added[i];
            if (!ArrayUtil.isInCmp(item, this.people, cmpPIds))
                this.people.push(item);
        }
        this.setBloodhound();
        var promise = this.bh.initialize();
        promise.fail(function () {
            WebUtil.error('failed to load the suggestion engine');
        });
        this.resetTypeahead();
    }

    /**
     * Removes all people from the current state that have been added manually
     */
    public resetAdded() {
        this.temporarily_added = [];
        this.temporarily_removed = [];
    }

    /**
     * Resets the typeahead suggestion engine
     * Note: We get an error in typescript in the second line when setting typescript. This is not our fault.
     * ALso see typeahead docs, they do the same:
     * @link https://github.com/twitter/typeahead.js
     */
    public resetTypeahead() {
        var that = this;
        var personInput = $('#person-input');
        personInput.val("");
        personInput.typeahead('destroy');
        personInput.typeahead(null, { //
            highlight: true,
            name: 'data',
            displayKey: 'name',
            source: that.bh,
            templates: {
                suggestion: function (data) {
                    var elem = $('<div class="noselect"></div>');
                    elem.text(StringUtil.shortify(data.name, 45));
                    return WebUtil.getOuterHTML(elem);
                }
            }
        });
    }

    /**
     * Sets up the bloodhound suggestion engine: This is the place where you have to go to find how exactly data is received from the server
     * @link http://stackoverflow.com/questions/25419972/twitter-typeahead-add-custom-data-to-dataset
     */
    private setBloodhound() {
        var that = this;
        this.bh = new Bloodhound({
            datumTokenizer: function (a) {
                return Bloodhound.tokenizers.whitespace(a.name);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            initialize: true,
            identify: function (item) {
                return item.p_id;
            },
            sorter: cmpNames,
            remote: {
                url: StringUtil.url('get/activitypeople') + '?activity_id=' + ac_id + that.formatTemporarily_added() + '&search=' + that.search,
                filter: function (data) {
                    if (data) {
                        return that.processNewSuggestionData(data.data);
                    } else {
                        return {};
                    }
                }
            }
        });
    }

    /**
     * Removes an item from the suggestions: This means it will no longer be suggested. It also assumes,
     * the item has been added to the activity
     * @param data
     */
    public removeItemFromSuggestions(data:ParticipantData) {
        this.temporarily_added.push(data);
        ArrayUtil.removeFromArrayCmp(data, this.temporarily_removed, cmpPIds);
    }

    /**
     * Parallel function to the latter: Adds an item to the suggestions and assumes it has been removed from the activity
     * @param data
     */
    public addItemToSuggestions(data:ParticipantData) {
        this.temporarily_removed.push(data);
        ArrayUtil.removeFromArrayCmp(data, this.temporarily_added, cmpPIds);
    }

    /**
     * Processes some new data: Adds all the people not yet in suggestions to them
     * Also considers the people just added to the activity
     * Also considers the people just removed from the activity
     * Also updates the "people"-variable to the return value of the function
     * @param data
     * @returns ParticipantEntity[]
     */
    private processNewSuggestionData(data:ParticipantData[]) {
        var output;
        if (this.people != null && this.people.length > 0 && this.people[0] != null) {
            output = this.people;
        } else {
            output = [];}
        $.each(data, function (k, v) {
            if (!ArrayUtil.isInCmp(v, output, cmpPIds)){ output.push(v) }
        });

        $.each(this.temporarily_removed, function (k, v) {
            if (!ArrayUtil.isInCmp(v, output, cmpPIds)){ output.push(v) }
        });
        $.each(this.temporarily_added, function (k, v) {
            if (ArrayUtil.isInCmp(v, output, cmpPIds)) { ArrayUtil.removeFromArrayCmp(v, output, cmpPIds); }
        });
        this.people = output;
        return output;
    }

    /**
     * Helper function for the API request: Adds an URL argument for every person temporarily added to the activity
     * These people should not be displayed in the suggestions
     * @returns {string}
     */
    private formatTemporarily_added() {
        var tA = this.temporarily_added;
        var query = '';
        for (var i = 0; i < tA.length; i++) {
            var pa = tA[i];
            query += encodeURIComponent('&temporarily_added[]=' + pa.p_id);
        }
        return query;
    }
}

/**
 * Defines the menu/table on the left of the view. Also responsible for all the buttons and their functions
 * @version 0.0.9
 */
class ActivityTable {

    private pagination:JQuery; //the object for jQuery pagination
    private table:JQuery; //the table on the left side
    private saveButton:JQuery; //the save-button
    private addButton:JQuery;
    private duplicateButton:JQuery;
    private hideButton:JQuery;
    private targetGroupButton:JQuery;
    private search:string;
    private page:number;
    private loader;
    private content:ActivityInformation;

    /**
     * Loads up all of the information and sets up the instance variables
     */
    public load(content:ActivityInformation, page) {
        this.loader = LoaderManager.createLoader($('#table-body'));
        var that = this;
        LoaderManager.showLoader((this.loader), function () {
            that.content = content;
            that.pagination = $('#pagination');
            that.table = $('#table-body');
            that.search = '';
            that.page = isNaN(page) ? 1 : page;
            that.updateTable();
            that.setUp();
        });
        LoaderManager.hideLoader(this.loader, function () {
            LoaderManager.destroyLoader(that.loader);
        });
    }

    /**
     * Creates the basic structure of the table
     */
    private setUp() {
        this.setUpButtons();
        this.setUpPagination();
        this.setUpActivitySearch();
        this.activateButtons();
    }

    /**
     * Adding the content to the table.
     * @since 0.0.6
     */
    private updateTable() {
        var that = this;
        AJAX.get(StringUtil.url('get/activities/?page=' + that.page + '&search=' + that.search, false), function (data:MenuData) {
            if (data.count < (that.page - 1) * 10) {
                that.pagination.pagination('selectPage', data.count / 10 - data.count % 10);
                return;
            }
            that.pagination.pagination('updateItems', data.count);
            displayNumItemsInDiv(data.count, PAGINATION_ITEMS_DEFAULT_ID);
            that.table.html('');
            if (data.count > 0) {
                that.addDataToTable(data);
            } else {
                that.table.append('<tr><td colspan="4" class="text-center"><b>Nothing to display . . .</b></td></tr>');
            }
        }, function (message:string) {
            WebUtil.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }

    /**
     * Performs a post request in order to create a new activity based on the information received in the modal
     * @param name
     * @param startDate
     * @param endDate
     * @param id
     */
    static newActivity(name:string, startDate:string, endDate:string, id:number) {
        var args = {
            action: 'create',
            activity_name: name,
            start_date: startDate,
            end_date: endDate
        };
        if (id > 0) {
            args['id'] = id;
        }
        AJAX.post(StringUtil.url('post/activity'), args,
            function (response:any) {
                WebUtil.to('activity/view/' + response.activity_id);
            }, function (message:string) {
                WebUtil.error('An error has occurred during the process of creating the activity. Error message: ' + message);
            });
    }

    /**
     * Creates a new activity specified by the user. Pops up a modal to get name/start/end date and then goes to the view
     */
    private addActivity(e) {
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
                            var startDate = DateUtil.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                            var endDate = DateUtil.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
                            ActivityTable.newActivity(name, startDate, endDate, -1);
                        }
                    }
                }
            }
        );
    }

    /**
     * The function called when the user clicks on the duplicate activity button: Brings up the modal and calls the API
     * @param e
     */
    private duplicateActivity(e) {
        e.preventDefault();
        bootbox.dialog({
                title: 'Duplicating activity',
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
                            var startDate = DateUtil.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                            var endDate = DateUtil.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
                            ActivityTable.newActivity(name, startDate, endDate, ac_id);
                        }
                    }
                }
            }
        );
    }

    /**
     * Comes up when the user clicks on the hide button: Confirms user's selection and then performs the right API call
     * @param id
     */
    private hideActivity(id) {
        bootbox.confirm('Are you sure you want to hide this activity? The data won\'t be deleted and can be restored later.', function (result) {
            if (result) {
                AJAX.post(StringUtil.url('post/activity'), {
                    action: 'hide',
                    activity_id: id
                }, function (response:any) {
                    WebUtil.to('activity');
                }, function (message:string) {
                    WebUtil.error('An error has occurred while hiding activity. Error message: ' + message);
                });
            }
        });
    }

    /**
     * Sets up the JQuery pagination plugin
     * @since 0.0.4
     */
    private setUpPagination() {
        var that = this;
        this.pagination.unbind('click');
        this.pagination.pagination({
            items: 0,
            itemsOnPage: 10,
            currentPage: that.page,
            onPageClick: function (page, event) {
                if (event != null) {
                    event.preventDefault();
                }
                that.page = page;
                that.updateTable();
            }
        });
    }


    /**
     * Links up the button for adding activities with the JS
     */
    private setUpButtons() {
        var that = this;
        this.saveButton = $('#save-activity');
        this.addButton = $('#add-activity');
        this.duplicateButton = $('#duplicate-activity');
        this.hideButton = $('#hide-activity');
        //this.targetGroupButton = $("#target-button");
        this.addButton.unbind('click');
        this.duplicateButton.unbind('click');
        this.hideButton.unbind('click');
        this.addButton.click(this.addActivity);
        this.duplicateButton.click({id: ac_id}, this.duplicateActivity);
        this.hideButton.click(function () {
            that.hideActivity.call(null, ac_id)
        });
    }

    /**
     * Adds the keyup event, so that the API request is automatically being done after the user didn't press anything
     */
    private setUpActivitySearch() {
        var timer = null;
        var that = this;
        $('#activities-search').keyup(function () {
            clearTimeout(timer);
            that.search = encodeURIComponent($(this).val());
            timer = setTimeout(function () {
                that.updateTable();
            }, AJAX_DELAY);
        });
    };

    /**
     * Removes all of the disabled-classes from the buttons
     */
    private activateButtons() {
        this.saveButton.removeClass('btn-warning');
        this.saveButton.addClass('btn-success');
        this.saveButton.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>No changes.');
        this.addButton.removeClass('disabled');
        this.duplicateButton.removeClass('disabled');
        this.hideButton.removeClass('disabled');
        //this.targetGroupButton.removeClass('disabled');
    }

    /**
     * With the data of all the activities, it successively creates the rows for each activity
     * @param data
     */
    private addDataToTable(data:MenuData) {
        if (isNaN(ac_id)) {
            ac_id = parseInt(data.activities[0].id);
        }
        for (var i = 0; i < data.activities.length; i++) {
            var item:ShortActivityData = data.activities[i];
            this.addRowToTable(item, parseInt(item.id) == ac_id);
        }
    }

    /**
     * Successively adds the parameters to one row and adds it to the DOM.
     * If the current row should be the current programme (passed by parameter), it will highlight it
     * @param data
     * @param active
     */
    private addRowToTable(data:ShortActivityData, active:boolean) {
        var that = this;
        var row:JQuery;
        var name = $('<td></td>');
        row = $('<tr></tr>');
        name.text(StringUtil.shortify(data.name, 22));
        row.append(name);
        var date = $('<td class="undefined text-right"></td>');
        var field = new DataDateRange({
            startDate: data.start_date,
            endDate: data.end_date
        });
        var small = $('<small></small>');
        field.renderPlain(small);
        date.append(small);
        row.append(date);
        row.click(function () {
            that.content.load(parseInt(data.id), that.content.existingPeople);
            that.load(that.content, that.page);
            ac_id = parseInt(data.id);
            window.history.pushState("", "", StringUtil.url('activity/view/' + data.id));
        });
        row.addClass('selectionItem');
        row.addClass('clickable');
        if (active) {
            row.addClass('activeItem');
            row.removeClass('selectionItem');
        }
        this.table.append(row);
    }
}

/**
 * Carries out all the tasks related to displaying the actual information of one activity on the right of the view
 * @since 0.0.7
 */
class ActivityInformation {

    // @todo Perhaps use an interface for some of these variables?

    private peopleTable:JQuery; //the table for all of the people in the activity
    private title:JQuery; //the activity title
    private targetComment:JQuery; //the comment for the target group of the activity
    private startDate:JQuery; //the activity start date
    private endDate:JQuery; //the activity end date
    public existingPeople:PeopleField; //the object for Twitter Typeahead to suggest people to add to the activity
    //private activeTargetGroup:TargetGroupData; //the current target group selection
    private people:ParticipantData[]; //the people in the activity
    private addedPeople:ParticipantData[] = []; //the people that have been added to the activity since page was loaded
    private removedPeople:ParticipantData[] = []; //the people that have been removed from the activity since page was loaded
    private onPage:number; //the page on which the activity is
    private id:number; //the activity id

    /**
     * Since this is the object responsible for the activity, it is necessary that this knows of the activity id
     * @returns {number}
     */
    public getId() {
        return this.id;
    }

    /**
     * Loads up all of the information and sets up the instance variables
     */
    public load(id:number, existingPeople:PeopleField) {
        var loader = LoaderManager.createLoader($('#activityContent'));
        var that = this;
        LoaderManager.showLoader((loader), function () {
            that.existingPeople = existingPeople;
            that.peopleTable = $('#existingPeople');
            that.id = id;
            ac_id = id;
            //that.activeTargetGroup = null;
            that.setUp();
            that.existingPeople.load();
            that.existingPeople.resetBloodhound();
            that.makeLinkWithSuggestions();
        });
        LoaderManager.hideLoader(loader, function () {
            LoaderManager.destroyLoader(loader);
        });
    }

    /**
     * Creates the basic structure of the table
     */
    private setUp() {
        var that = this;
        this.removedPeople = [];
        AJAX.get(StringUtil.url('get/activity/?id=' + that.id, false), function (data:DetailActivityData) {
            var breadcrumbs = $('#nav-breadcrumbs');
            breadcrumbs.find('li:nth-child(3)').text('Activity #' + that.id + ': ' + data.name);
            //that.activeTargetGroup = data.target_groups.active == null ? data.target_groups.data[0] : data.target_groups.active;
            that.people = data.participants;
            that.onPage = data.page;
            that.displayTitle(data.name);
            that.displayPeople();
            //that.displayTargetGroup(data.target_groups.data);
            that.displayComment(data.target_group_comment);
            that.displayStartDate(data.start_date);
            that.displayEndDate(data.end_date);
        }, function (message:string) {
            WebUtil.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }

    /**
     * Sends the current object state to the server, resets the object state to account for changes
     * @todo Find out why we perform 3-4 POST-requests each time we add a person
     */
    private save() {
        var saveButton = $('#save-activity');
        this.displaySaving(saveButton);
        this.savePeople();
        var data = this.getObjectState();
        data['action'] = 'update';
        var that = this;
        AJAX.post(StringUtil.url('post/activity'), data, function (response:any) {
            that.displaySuccessfulSave(saveButton);
        }, function (message:string) {
            that.displaySaveFailure(saveButton);
            WebUtil.error('An error has occurred while saving. Error message: ' + message);
        });
        this.resetPeople();
    }

    /**
     * Sets up the button such that it would show that we currently save the activity
     * @param saveButton
     */
    private displaySaving(saveButton) {
        saveButton.removeClass('btn-danger');
        saveButton.removeClass('btn-success');
        saveButton.addClass('btn-warning');
        saveButton.html('<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Saving...');
    };

    /**
     * Sets up the button such that it indicates saving failed
     * @param saveButton
     */
    private displaySaveFailure(saveButton) {
        saveButton.removeClass('btn-warning');
        saveButton.addClass('btn-danger');
        saveButton.html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Saving failed.');
    };

    /**
     * Sets up the button such that it shows saving has been successful
     * @param saveButton
     */
    private displaySuccessfulSave(saveButton) {
        saveButton.removeClass('btn-warning');
        saveButton.addClass('btn-success');
        saveButton.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Changes saved.');
    };

    /**
     * Gets all of the object's information and returns it as an object
     * @returns {{action: string, activity_id: number, activity_name: string, target_group: string, target_group_comment: string, start_date: string, end_date: string, added_people: ParticipantData[], removed_people: ParticipantData[]}}
     */
    private getObjectState() {
        var sd:Date = this.startDate.datepicker('getDate');
        var startDate:string = DateUtil.toMysqlFormat(sd);
        var ed:Date = this.endDate.datepicker('getDate');
        var endDate:string = DateUtil.toMysqlFormat(ed);
        return {
            activity_id: this.getId(),
            activity_name: this.title.val(),
            //target_group: this.activeTargetGroup == null ? null : this.activeTargetGroup.id,
            target_group_comment: this.targetComment.val(),
            start_date: startDate,
            end_date: endDate,
            added_people: this.addedPeople,
            removed_people: this.removedPeople
        };
    };

    /**
     * Adjusts the people property to account for changes in people added/removed from the programme
     */
    private savePeople() {
        for (var i = 0; i < this.addedPeople.length; i++) {
            var item = this.addedPeople[i];
            if (!ArrayUtil.isInCmp(item, this.people, cmpPIds))
                this.people.push(item);
        }
        for (var i = 0; i < this.removedPeople.length; i++) {
            var item = this.removedPeople[i];
            ArrayUtil.removeFromArrayCmp(item, this.people, cmpPIds);
        }
    };

    /**
     * Resets the suggestion engine and re-establishes the link to it (submits etc)
     */
    private resetPeople() {
        this.displayPeople();
        this.existingPeople.resetBloodhound();
        this.makeLinkWithSuggestions();
    }

    /**
     * Displays the title of the activity in the dedicated textfield
     * @param title
     */
    private displayTitle(title:string) {
        this.title = $('#activity-title');
        this.title.empty();
        this.title.val(title);
        var timer;
        var that = this;
        this.title.on('input propertychange change', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                that.save();
            }, AJAX_DELAY);
        });
    }

    /**
     * Adds the submit functions for the suggestion engine: Either Enter-press or on-click can add a person to the activity
     * Subsequently adds the person to the activity
     */
    private makeLinkWithSuggestions() {
        var ta = $('.twitter-typeahead');
        var that = this;
        ta.keyup(function (e) {
            if (e.which == 13) {
                $('.tt-suggestion:first-child', this).trigger('click');
            }
        });
        ta.on('typeahead:selected', {that: that}, addItemFromSuggestion);
    }

    /**
     * Shows the target group as dropdown
     * @param options
     */
    /*
    private displayTargetGroup(options:TargetGroupData[]) {
        var that = this;
        var dropD = $('#target-dropdown');
        dropD.empty();
        var bt = $('#target-button');
        bt.empty();
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        bt.text(this.activeTargetGroup.name);
        bt.append('<span class="caret"></span>');
        for (var i = 0; i < options.length; i++) {
            var option = $('<li data-name="' + options[i].name + '" data-id="' + options[i].id + '"></li>');
            var link = $('<a></a>');
            link.text(options[i].name);
            option.append(link);
            if (options[i].id == this.activeTargetGroup.id) {
                option.addClass('disabled');
            } else {
                option.click(function () {
                    that.activeTargetGroup.id = $(this).data('id');
                    that.activeTargetGroup.name = $(this).data('name');
                    dropD.empty();
                    bt.empty();
                    that.displayTargetGroup(options);
                    that.save();
                });
                option.addClass('noselect');
            }
            dropD.append(option);
        }
    }*/

    /**
     * Displays the comment for the target group
     * @param initialData
     */
    private displayComment(initialData:string) {
        var timer;
        var that = this;
        this.targetComment = $('#target-comment');
        this.targetComment.empty();
        this.targetComment.val(initialData);
        this.targetComment.on('input propertychange change', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                that.save();
            }, AJAX_DELAY);
        });
    }


    /**
     * Displays the start date of the activity
     * @param sqlDate:string
     */
    private displayStartDate(sqlDate:string) {
        var timer;
        var that = this;
        var startD:string = DateUtil.formatNumberDate(DateUtil.parseSQLDate(<string> sqlDate));
        $('#start-date').empty();
        this.startDate = WebUtil.getDatePicker(startD, "start-date-picker");
        $('#start-date').append(this.startDate);
        this.startDate = $('#start-date-picker'); //otherwise it would not work properly
        this.startDate.on('input propertychange change', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                that.save();
            }, AJAX_DELAY);
        });
    }

    /**
     * Displays the end date of the activity
     * @param sqldate:string
     */
    private displayEndDate(sqldate:string) {
        var timer;
        var that = this;
        $('#end-date').empty();
        var endD:string = DateUtil.formatNumberDate(DateUtil.parseSQLDate(<string> sqldate));
        this.endDate = WebUtil.getDatePicker(endD, "end-date-picker");
        $('#end-date').append(this.endDate);
        this.endDate = $('#end-date-picker'); //otherwise it would not work properly
        this.endDate.on('input propertychange change', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                that.save();
            }, AJAX_DELAY);
        });
    }

    /**
     * Creates the table with all the people in a activity
     * In doing so, it creates a copy of the people in the activity and actually considers that copy when displaying people
     */
    private displayPeople() {
        var people = this.people;
        for (var i = 0; i < this.addedPeople.length; i++) {
            var item = this.addedPeople[i];
            if (!ArrayUtil.isInCmp(item, people, cmpPIds)) {
                people.push(item)
            }
        }
        people = ArrayUtil.arraySubtract(people, this.removedPeople);
        people.sort(cmpNames);
        this.peopleTable.empty();
        //console.log(people);
        for (var i = 0; i < people.length; i++) {
            var person:ParticipantData = people[i];
            this.displayPerson(person);
        }
        displayNumItemsInDiv(people.length, PAGINATION_ITEMS_ACTIVITYPARTICIPANTS_ID);
    }

    /**
     * Adds a row to the table showing the people currently in the activity
     * @param person:ParticipantData
     */
    private displayPerson(person:ParticipantData) {
        var that = this;
        var row = $('<td class="col-md-11 selectionItem clickable"></td>');
        row.text(StringUtil.shortify(person.name, 40));
        var removeButton = $('<td class="col-md-1">' +
            '<button type="button" class="btn btn-xs btn-primary" style="display:block; text-align:center">' +
            '<small>' +
            '<span class="glyphicon glyphicon-remove" aria-hidden="false">' +
            '</span></small></button></td>');
        that.addRemoveClick(removeButton, person);
        var fullRow = $('<tr class="row"></tr>');
        fullRow.append(row);
        fullRow.append(removeButton);
        that.addOnClickToRow(row, person.r_id);
        that.peopleTable.append(fullRow);
    }

    /**
     * Adds the onclick function: If a user wants to remove a person, we have to add it to the suggestion field
     * Also have to remove it from the people table
     * @param button:JQuery
     * @param person:ParticipantData
     */
    private addRemoveClick(button, person:ParticipantData) {
        var that = this;
        var timer;

        function removePerson(e) {
            var c = e.data;
            ArrayUtil.removeFromArrayCmp(c.person, c.that.addedPeople, cmpPIds);
            if (!ArrayUtil.isInCmp(c.person, c.that.removedPeople, cmpPIds))
                c.that.removedPeople.push(c.person);
            c.that.existingPeople.addItemToSuggestions(c.person);
            clearTimeout(timer);
            timer = setTimeout(function () {
                //console.log('saving because person removed');
                c.that.save();
                c.that.existingPeople.resetBloodhound();
                c.that.makeLinkWithSuggestions();
            }, AJAX_DELAY);
        }

        button.click({person: person, that: that}, removePerson);
    }

    /**
     * Adds an on-click event to a row in the table in which all the people going to an activity are displayed
     * @param row:JQuery
     * @param id:string
     */
    private addOnClickToRow(row:JQuery, id:string) {
        row.click(function () {
            WebUtil.to('record/view/' + id);
        });
    }
}

/**
 * Compare function for property name
 * @param a:Object
 * @param b:Object
 * @returns {number}
 */
function cmpNames(a, b) {
    if (a.name > b.name)
        return 1;
    else if (b.name > a.name)
        return -1;
    else return 0;
}

/**
 * Compare function for property id
 * @param a:Object
 * @param b:Object
 * @returns {number}
 */
function cmpPIds(a, b) {
    if (parseInt(a.p_id) > parseInt(b.p_id))
        return 1;
    if (parseInt(b.p_id) > parseInt(a.p_id))
        return -1;
    return 0;
}


/**
 * Function for handling the event of adding a new person
 * @todo Put this in ActivityInformation
 * @param e
 * @param item:ParticipantData
 */
function addItemFromSuggestion(e, item:ParticipantData) {
    var c = e.data;
    //console.log('adding activityinfo array added people name ' + item.name);
    ArrayUtil.removeFromArrayCmp(item, c.that.removedPeople, cmpPIds);
    if (!ArrayUtil.isInCmp(item, c.that.addedPeople, cmpPIds))
        c.that.addedPeople.push(item);
    c.that.existingPeople.removeItemFromSuggestions(item);
    c.that.save();
    c.that.existingPeople.resetBloodhound();
    c.that.makeLinkWithSuggestions();
}

function displayNumItemsInDiv(numItems:number, divName:string) {
    var paginationNumItemsDiv = $('#' + divName);
    paginationNumItemsDiv.empty();
    var itemText:string;
    if(numItems == 1) {
        itemText = "1 item";
    } else {
        itemText = numItems + " items";
    }
    var textContainer = $("<span class=" + PAGINATION_ITEMS_DEFAULT_ID + "></span>");
    textContainer.text(itemText);
    paginationNumItemsDiv.append(textContainer);
}

$(document).ready(function () {
    var id = StringUtil.extractId(window.location.toString());
    var hidden = $('input[name="hiddenField"]');
    var page = hidden.val();
    //console.log(page);
    if (isNaN(id)) {
        var breadcrumbs = $('#nav-breadcrumbs');
        var fullLink = breadcrumbs.find('li:nth-child(2)').find("a").attr("href");
        id = StringUtil.extractId(fullLink);
    }
    ac_id = id;
    var activity:ActivityInformation = new ActivityInformation();
    var existingPeopleField:PeopleField = new PeopleField();
    activity.load(id, existingPeopleField);
    new ActivityTable().load(activity, page);
});

