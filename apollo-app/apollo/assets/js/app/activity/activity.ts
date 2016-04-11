///<reference path="../ajax.ts"/>
///<reference path="../scripts.ts"/>
///<reference path="../jquery.d.ts"/>
///<reference path="../inputs.ts"/>
///<reference path="../bootbox.d.ts"/>

/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.1.7
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
    name:string,
    id:string
}

interface TargetGroupData {
    name:string,
    id:string
}

interface TargetGroupObject {
    active: TargetGroupData,
    data: TargetGroupData[]
}

interface DetailActivityData {
    error:Error,
    page:number,
    name:string,
    target_groups:TargetGroupObject,
    target_group_comment:string,
    start_date:string,
    end_date:string,
    participants:ParticipantData[]
}

/**
 * Class to store the token field (the field to add/remove users from a activity)
 * @version 0.0.8
 */
class PeopleField {
    private activity_id:number;
    private search:string;
    private bh:BloodHound;
    private people:ParticipantData[]=  [];
    private temporarily_added:ParticipantData[] = []; //people that are temporarily added to the activity (-> not saved). These should not be suggested.
    private temporarily_removed:ParticipantData[] = []; //people that are temporarily added to the suggestions. These items were removed from the activity

    /**
     * Initialises the object to the required state
     * @param activity_id
     */
    public load(activity_id:number){
        this.activity_id = activity_id;
        this.search = '';
        this.resetAdded();
        this.resetBloodhound();
    }

    /**
     * Just saves the current state of suggestions and then sets up bloodhound again
     */
    public resetBloodhound() {
        for(var item in this.temporarily_added){
            Util.removeFromArrayCmp(item, this.people, cmpIds);
        }
        for(var item in this.temporarily_removed){
            if(!Util.isInCmp(item, this.people, cmpIds))
                this.people.push(item);
        }
        this.setBloodhound();
        var promise = this.bh.initialize();
        promise.fail(function() { Util.error('failed to load the suggestion engine');});
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
     */
    public resetTypeahead() {
        var that = this;
        $('#person-input').val("");
        $('#person-input').typeahead('destroy');
        $('#person-input').typeahead({
            highlight: true
        }, {
            name: 'data',
            displayKey: 'name',
            //allowDuplicates: false,
            source: that.bh.ttAdapter(),
            templates: {
                suggestion: function (data) {
                    var elem = $('<div class="noselect"></div>');
                    elem.text(Util.shortify(data.name, 45));
                      var str = Util.getOuterHTML(elem);
                    return str;
                }
            }
        });
    }

    /**
     * Sets up the bloodhound suggestion engine: This is the place where you have to go to find how exactly data is received from the server
     * http://stackoverflow.com/questions/25419972/twitter-typeahead-add-custom-data-to-dataset
     */
    private setBloodhound() {
        var that = this;
        this.bh = new Bloodhound({
            datumTokenizer: function (a) {
                return Bloodhound.tokenizers.whitespace(a.name);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            initialize: true,
            identify: function(item) {return item.id;},
            sorter: cmpNames,
            remote: {
                url: Util.url('get/activitypeople') + '?activity_id=' + that.activity_id + that.formatTemporarily_added() + '&search=' + that.search,
                filter: function (data) {
                    if (!data) {
                        return {};
                    } else {
                        //Adds all of the people to the suggestion engine
                        function carryout(data) {
                            var output;
                            if(that.people != null && that.people.length > 0 && that.people[0] != null)
                                output = that.people;
                            else
                                output = [];
                            $.each(that.temporarily_added, function(k,v){
                                if(Util.isInCmp(v, output, cmpIds)){
                                    Util.removeFromArrayCmp(v, output, cmpIds);
                                }
                            });
                            $.each(data, function(k,v){
                                if(!Util.isInCmp(v, output, cmpIds))
                                    output.push(v);
                            });
                            $.each(that.temporarily_removed, function(k,v){
                                if(!Util.isInCmp(v, output, cmpIds))
                                    output.push(v);
                            });
                            that.people = output;
                            return output;
                        }
                        var co = carryout(data.data);
                        return co;

                    }

                },
            },
            rateLimitWait: 500
        });
    }

    /**
     * Removes an item from the suggestions: This means it will no longer be suggested. It also assumes,
     * the item has been added to the activity
     * @param data
     */
    public removeItemFromSuggestions(data:ParticipantData) {
        this.temporarily_added.push(data);
        Util.removeFromArrayCmp(data, this.temporarily_removed, cmpIds);
    }

    /**
     * Parallel function to the latter: Adds an item to the suggestions and assumes it has been removed from the activity
     * @param data
     */
    public addItemToSuggestions(data:ParticipantData) {
        this.temporarily_removed.push(data);
        Util.removeFromArrayCmp(data, this.temporarily_added, cmpIds);
    }

    /**
     * Helper function for the API request: Adds an URL argument for every person temporarily added to the activity
     * These people should not be displayed in the suggestions
     * @returns {string}
     */
    private formatTemporarily_added ()
    {
        var tA = this.temporarily_added;
        var query = '';
        for(var i = 0; i < tA.length; i++) {
            var pa = tA[i];
            query += '&temporarily_added[]=' + pa.id;
        }
        return query;
    }

    public setId(id:number) {
        this.activity_id = id;
    }
}

/**
 * Defines the menu/table on the left of the view. Also responsible for all the buttons and their functions
 * @version 0.0.7
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
        LoaderManager.showLoader((this.loader), function() {
            that.content = content;
            that.pagination = $('#pagination');
            that.table = $('#table-body');
            that.search = '';
            that.page = isNaN(page)? 1 : page;
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
        if(id > 0) {
            args['id'] = id;
        }
        AJAX.post(Util.url('post/activity'), args,
            function (response:any) {
            Util.to('activity/view/' + response.activity_id);
        }, function (message:string) {
            Util.error('An error has occurred during the process of creating the activity. Error message: ' + message);
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
                            var startDate = Util.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                            var endDate = Util.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
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
    private duplicateActivity (e) {
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
                            var startDate = Util.toMysqlFormat(modal.find('#add-start-date').datepicker('getDate'));
                            var endDate = Util.toMysqlFormat(modal.find('#add-end-date').datepicker('getDate'));
                            ActivityTable.newActivity(name, startDate, endDate, e.data.id);
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
                AJAX.post(Util.url('post/activity'), {
                    action: 'hide',
                    activity_id: id
                }, function (response:any) {
                    Util.to('activity/');
                }, function (message:string) {
                    Util.error('An error has occurred while hiding activity. Error message: ' + message);
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
        this.pagination.pagination({
            items: 0,
            itemsOnPage: 10,
            currentPage: that.page,
            onPageClick: function (page, event) {
                if(event != null) {
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
        var active = this.content.getId();
        this.saveButton = $('#save-activity');
        this.addButton = $('#add-activity');
        this.duplicateButton = $('#duplicate-activity');
        this.hideButton = $('#hide-activity');
        this.targetGroupButton = $("#target-button");
        this.addButton.click(this.addActivity);
        this.duplicateButton.click({id: active}, this.duplicateActivity);
        this.hideButton.click(function() {that.hideActivity.call(null, active)});
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
        this.targetGroupButton.removeClass('disabled');
    }

    /**
     * With the data of all the activities, it successively creates the rows for each activity
     * @param data
     */
    private addDataToTable(data:MenuData) {
        var activeId = this.content.getId();
        if (isNaN(activeId)) {
            activeId = parseInt(data.activities[0].id);
        }
        for (var i = 0; i < data.activities.length; i++) {
            var item:ShortActivityData = data.activities[i];
            this.addRowToTable(item, parseInt(item.id) == activeId);
        }
    }

    /**
     * Successively adds the parameters to one row and adds it to the DOM.
     * If the current row should be the current programme (passed by parameter), it will highlight it
     * @param data
     * @param active
     */
    private addRowToTable(data:ShortActivityData, active:boolean) {
        var row:JQuery;
        var startD;
        var endD;
        var that = this;
        startD = Util.formatShortDate(Util.parseSQLDate(<string> data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(<string> data.end_date));
        row = $('<tr></tr>');
        var name = $('<td></td>');
        name.text(Util.shortify(data.name, 20));
        row.append(name);
        var date = $('<td></td>');
        date.text(startD + '-' + endD);
        row.append(date);
        row.click(function() {
            that.displayActivity.call(null, data.id);
        });
        row.addClass('selectionItem');
        row.addClass('clickable');
        if(active){
            row.addClass('activeItem');
            row.removeClass('selectionItem');
        }
        this.table.append(row);
    }

    private displayActivity(activityId:string) {
        Util.to('/activity/view/' + activityId);
    }
}

/**
 * Carries out all the tasks related to displaying the actual information of one activity on the right of the view
 * @since 0.0.6
 */
class ActivityInformation {

    private peopleTable:JQuery; //the table for all of the people in the activity
    private title:JQuery; //the activity title
    private targetComment:JQuery; //the comment for the target group of the activity
    private startDate:JQuery; //the activity start date
    private endDate:JQuery; //the activity end date
    private existingPeople:PeopleField; //the object for Twitter Typeahead to suggest people to add to the activity
    private activeTargetGroup:TargetGroupData; //the current target group selection
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
     * Similar to getId()
     * @returns {number}
     */
    public getPage() {
        return this.onPage;
    }

    /**
     * Loads up all of the information and sets up the instance variables
     */
    public load(id:number, existingPeople:PeopleField){
        var loader = LoaderManager.createLoader($('#activityContent'));
        var that = this;
        LoaderManager.showLoader((loader), function() {
            that.existingPeople = existingPeople;
            that.peopleTable = $('#existingPeople');
            that.id = id;
            that.activeTargetGroup = null;
            that.setUp();
            that.existingPeople.load(id);
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
    private setUp(){
        var that = this;
        this.removedPeople = [];
        AJAX.get(Util.url('get/activity/?id=' + that.id, false), function(data:DetailActivityData) {
            var breadcrumbs = $('#nav-breadcrumbs');
            breadcrumbs.find('li:nth-child(3)').text('Activity #' + that.id + ': ' + data.name);
            that.activeTargetGroup = data.target_groups.active == null? data.target_groups.data[0] : data.target_groups.active;
            that.people = data.participants;
            that.onPage = data.page;
            that.displayTitle(data.name);
            that.displayPeople();
            that.displayTargetGroup(data.target_groups.data);
            that.displayComment(data.target_group_comment);
            that.displayStartDate(data.start_date);
            that.displayEndDate(data.end_date);
        }, function (message:string) {
            Util.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }

    /**
     * Sends the current object state to the server, resets the object state to account for changes
     */
    private save() {
        var saveButton = $('#save-activity');
        this.displaySaving(saveButton);
        this.savePeople();
        var data = this.getObjectState();
        var that = this;
        AJAX.post(Util.url('post/activity'), data, function (response:any) {
            that.displaySuccessfulSave(saveButton);
        }, function (message:string) {
            that.displaySaveFailure(saveButton);
            Util.error('An error has occurred while saving. Error message: ' + message);
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
     * @returns {{action: string, activity_id: number, activity_name: any, target_group: string, target_group_comment: any, start_date: string, end_date: string, added_people: ParticipantData[], removed_people: ParticipantData[]}}
     */
    private getObjectState() {
        var sd:Date = this.startDate.datepicker('getDate');
        var startDate:string = Util.toMysqlFormat(sd);
        var ed:Date = this.endDate.datepicker('getDate');
        var endDate:string = Util.toMysqlFormat(ed);
        var data = {
            action: 'update',
            activity_id: this.getId(),
            activity_name: this.title.val(),
            target_group: this.activeTargetGroup == null ? null : this.activeTargetGroup.id,
            target_group_comment: this.targetComment.val(),
            start_date: startDate,
            end_date: endDate,
            added_people: this.addedPeople,
            removed_people: this.removedPeople
        };
        return data;
    };

    /**
     * Adjusts the people property to account for changes in people added/removed from the programme
     */
    private savePeople() {
        for (var i = 0; i < this.addedPeople.length; i++) {
            var item = this.addedPeople[i];
            if (!Util.isInCmp(item, this.people, cmpIds))
                this.people.push(item);
        }
        for (var i = 0; i < this.removedPeople.length; i++) {
            var item = this.removedPeople[i];
            Util.removeFromArrayCmp(item, this.people, cmpIds);
        }
    };

    /**
     * Resets the suggestion engine and re-establishes the link to it (submits etc)
     */
    private resetPeople(){
        this.displayPeople();
        this.existingPeople.resetBloodhound();
        this.makeLinkWithSuggestions();
    }

    /**
     * Displays the title of the activity in the dedicated textfield
     * @param title
     */
    private displayTitle(title:string){
        this.title = $('#activity-title');
        this.title.val(title);
        var timer;
        var that = this;
        this.title.on('input propertychange change', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                that.save();
            }, AJAX_DELAY);
        });
    }

    /**
     * Adds the submit functions for the suggestion engine: Either Enter-press or on-click can add a person to the activity
     * Subsequently adds the person to the activity
     */
    private makeLinkWithSuggestions(){
        var ta = $('.twitter-typeahead');
        ta.keyup(function(e){
            if(e.which == 13) {
                $('.tt-suggestion:first-child', this).trigger('click');
            }
        });
        var that = this;
        ta.on('typeahead:selected', {that: that}, addItemFromSuggestion);
    }

    /**
     * Shows the target group as dropdown
     * @param options
     */
    private displayTargetGroup(options:TargetGroupData[]){
        var that = this;
        var dropD = $('#target-dropdown');
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        var bt = $('#target-button');
        bt.text(this.activeTargetGroup.name);
        bt.append('<span class="caret"></span>');
        for(var i = 0; i < options.length; i++) {
            var option = $('<li optionNameUnique="' + Util.escapeHTML(options[i].name) + '" optionIdUnique="' + Util.escapeHTML(options[i].id) + '"></li>');
            var link = $('<a></a>');
            link.text(options[i].name);
            option.append(link);
            var timer;
            if(options[i].id == this.activeTargetGroup.id) {
                option.addClass('disabled');
            } else {
                option.click(function() {
                    that.activeTargetGroup.id = $(this).attr('optionIdUnique');
                    that.activeTargetGroup.name = $(this).attr('optionNameUnique');
                    dropD.empty();
                    bt.empty();
                    that.displayTargetGroup(options);
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        that.save();
                    });
                });
                option.addClass('noselect');
            }
            dropD.append(option);
        }
    }

    /**
     * Displays the comment for the target group
     * @param initialData
     */
    private displayComment(initialData:string){
        this.targetComment = $('#target-comment');
        this.targetComment.val(initialData);
        var timer;
        var that = this;
        this.targetComment.on('input propertychange change', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                that.save();
            }, AJAX_DELAY);
        });
    }


    /**
     * Displays the start date of the activity
     * @param sqlDate
     */
    private displayStartDate(sqlDate:string){
        var startD:string = Util.formatNumberDate(Util.parseSQLDate(<string> sqlDate));
        this.startDate = Util.getDatePicker(startD, "start-date-picker");
        $('#start-date').append(this.startDate);
        this.startDate = $('#start-date-picker'); //otherwise it would not work properly
        var timer;
        var that = this;
        this.startDate.on('input propertychange change', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                that.save();
            }, AJAX_DELAY);
        });
    }

    /**
     * Displays the end date of the activity
     * @param sqldate
     */
    private displayEndDate(sqldate:string){
        var endD:string = Util.formatNumberDate(Util.parseSQLDate(<string> sqldate));
        this.endDate = Util.getDatePicker(endD, "end-date-picker");
        $('#end-date').append(this.endDate);
        this.endDate = $('#end-date-picker'); //otherwise it would not work properly
        var timer;
        var that = this;
        this.endDate.on('input propertychange change', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                that.save();
            }, AJAX_DELAY);
        });
    }

    /**
     * Creates the table with all the people in a activity
     * @param people
     */
    private displayPeople(){
        var people = this.people;
        for(var i = 0; i < this.addedPeople.length; i++){
            var item = this.addedPeople[i];
            if(!Util.isInCmp(item, people, cmpIds))
                people.push(item)
        }
        people = Util.arraySubtract(people, this.removedPeople);
        this.peopleTable.empty();
        people.sort(cmpNames);
        //console.log(people);
        for(var i = 0; i < people.length; i++) {
            var person:ParticipantData = people[i];
            this.displayPerson(person);
        }
    }

    /**
     * Adds a row to the table showing the people currently in the activity
     * @param person
     */
    private displayPerson(person:ParticipantData) {
        var that = this;
        var row = $('<td class="col-md-11 selectionItem clickable"></td>');
        var width = row.width();
        row.text(Util.shortify(person.name, 40));
        var removeButton = $('<td class="col-md-1">' +
            '<button type="button" class="btn btn-xs btn-default" style="display:block; text-align:center">' +
            '<small>' +
            '<span class="glyphicon glyphicon-remove" aria-hidden="false">' +
            '</span></small></button></td>');
        that.addRemoveClick(removeButton, person);
        var fullRow = $('<tr class="row"></tr>');
        fullRow.append(row);
        fullRow.append(removeButton);
        that.addOnClickToRow(row, person.id);
        that.peopleTable.append(fullRow);
    }

    /**
     * Adds the onclick function: If a user wants to remove a person, we have to add it to the suggestion field
     * Also have to remove it from the people table
     * @param button
     * @param person
     */
    private addRemoveClick(button, person:ParticipantData) {
        var that = this;
        var timer;
        function removePerson(e) {
            var c = e.data;
            Util.removeFromArrayCmp(c.person, c.that.addedPeople, cmpIds);
            if(!Util.isInCmp(c.person, c.that.removedPeople, cmpIds))
                c.that.removedPeople.push(c.person);
            c.that.existingPeople.addItemToSuggestions(c.person);
            clearTimeout(timer);
            timer = setTimeout(function() {
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
     * @param row
     * @param id
     */
    private addOnClickToRow(row:JQuery, id:string) {
        row.click(function() {
            Util.to('record/view/' + id);
        });
    }
}

/**
 * Compare function for property name
 * @param a
 * @param b
 * @returns {number}
 */
function cmpNames(a, b) {
    if (a.name > b.name)
        return 1;
    if (b.name > a.name)
        return -1;
    return 0;
}

/**
 * Compare function for property id
 * @param a
 * @param b
 * @returns {number}
 */
function cmpIds(a, b) {
    if (parseInt(a.id) > parseInt(b.id))
        return 1;
    if (parseInt(b.id) > parseInt(a.id))
        return -1;
    return 0;
}



/**
 * Function for handling the event of adding a new person
 * @param e
 * @param item
 */
function addItemFromSuggestion(e, item){
    var c = e.data;
    //console.log('adding activityinfo array added people name ' + item.name);
    Util.removeFromArrayCmp(item, c.that.removedPeople, cmpIds);
    if(!Util.isInCmp(item, c.that.addedPeople, cmpIds))
        c.that.addedPeople.push(item);
    c.that.existingPeople.removeItemFromSuggestions(item);
    var timer;
    clearTimeout(timer);
    timer = setTimeout(function() {
        //console.log('saving because person added');
        c.that.save();
        c.that.existingPeople.resetBloodhound();
        c.that.makeLinkWithSuggestions();
    });
}

$(document).ready(function () {
    var id = Util.extractId(window.location.toString());
    var hidden = $('input[name="hiddenField"]');
    var page = hidden.val();
    //console.log(page);
    if(isNaN(id)){
        var breadcrumbs = $('#nav-breadcrumbs');
        var fullLink = breadcrumbs.find('li:nth-child(2)').find("a").attr("href");
        var newId = Util.extractId(fullLink);
        id = newId;
    }
    var activity:ActivityInformation = new ActivityInformation();
    var existingPeopleField:PeopleField = new PeopleField();
    activity.load(id, existingPeopleField);
    var menu:ActivityTable = new ActivityTable().load(activity, page);
});

