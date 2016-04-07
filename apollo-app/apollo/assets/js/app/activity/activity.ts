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
 * @version 0.1.3
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

interface DetailActivityData {
    error:Error,
    page:number,
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
 * @version 0.0.6
 * TODO Dynamically update the table when new entries were added. Add "autosave"
 */
class PeopleField {
    private activity_id:number;
    private search:string;
    private bh:BloodHound;
    private temporarily_added:ParticipantData[] = []; //people that are temporarily added to the activity (-> not saved). These should not be suggested.
    private temporarily_removed:ParticipantData[] = []; //people that are temporarily added to the suggestions. These items were removed from the activity

    public load(activity_id:number){
        this.activity_id = activity_id;
        this.search = '';
        this.temporarily_added = [];
        this.temporarily_removed = [];
        this.setUp();
    }

    private setUp() {
        var that = this;
        this.resetBloodhound();
        /*$('#person-input').keydown(function(e) {
            that.search = encodeURIComponent($(this).val());
        }*/

    }

    public resetBloodhound() {
        this.setBloodhound();
        var promise = this.bh.initialize();
        promise.fail(function() { Util.error('failed to load the suggestion engine');});
        this.resetTypeahead();
    }

    public resetTypeahead() {
        //console.log('resetting typehead');
        var that = this;
        $('#person-input').val("");
        $('#person-input').typeahead('destroy');
        $('#person-input').typeahead({
            highlight: true
        }, {
            name: 'data',
            displayKey: 'name',
            allowDuplicates: false,
            source: that.bh.ttAdapter(),
            templates: {
                suggestion: function (data) {
                    var str = '';
                    if(!Util.isIn(data, that.temporarily_added))
                        str += '<div class="noselect">' + data.name + '</div>';
                    return str;
                }
            }
        });
    }

    //http://stackoverflow.com/questions/25419972/twitter-typeahead-add-custom-data-to-dataset

    private setBloodhound() {
        var that = this;
        this.bh = new Bloodhound({
            datumTokenizer: function (a) {
                return Bloodhound.tokenizers.whitespace(a.name);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            initialize: true,
            identify: function(item) {return item.id;},
            sorter: sortParticipants,
            remote: {
                url: Util.url('get/activitypeople') + '?activity_id=' + that.activity_id + that.formatTemporarily_added() + '&search=' + that.search,
                filter: function (data) {
                    if (!data) {
                        return {};
                    } else {

                        function destringify(data) {
                            ////console.log(data);
                            $.map(data, function (item) {
                                return {
                                    'id': item.id,
                                    'name': item.name
                                }
                            });
                        }

                        function carryout(data) {
                            //console.log(data);
                            var objs = destringify(data);
                            var output = [];
                            $.each(data, function(k,v){
                                if (!Util.isIn(v, that.temporarily_added) && !Util.isIn(v, that.temporarily_removed)){
                                    output.push(v);
                                }
                            });
                            //console.log('added all normal people:');
                            //console.log(output);
                            $.each(that.temporarily_removed, function(k,v){
                               output.push(v);
                            });
                            //console.log('after adding temporarily_removed:');
                            //console.log(output);
                            return output;
                        }
                        var co = carryout(data.data);
                        //console.log('temp added (carryout): ');
                        //console.log(that.temporarily_added);
                        //console.log('temp removed (carryout): ');
                        //console.log(that.temporarily_removed);
                        //console.log('after adding:');
                        //console.log(co);
                        return co;

                    }

                },
            },
            rateLimitWait: 100
        });
    }

    public removeItemFromSuggestions(data:ParticipantData) {
        this.temporarily_added.push(data);
        //console.log('removing item from suggestions, adding to temporarily_added ' + data.name + data.id);
        if (Util.isIn(data, this.temporarily_removed)) {
            Util.removeFromArray(data, this.temporarily_removed);
            //console.log('removing item from suggestions, removing from temporarily removed ' + data.name + data.id);
        }
    }

    public addItemToSuggestions(data:ParticipantData) {
        this.temporarily_removed.push(data);
        //console.log('adding item to suggestions, adding to temporarily removed ' + data.name + data.id);
        if(Util.isIn(data, this.temporarily_added)) {
            Util.removeFromArray(data, this.temporarily_added);
            //console.log('adding item to suggestions, removing from temporarily_added ' + data.name + data.id);

        }
    }

    public addDataToSuggestions(data:ParticipantData[]) {
        $.each(data, function(key, obj){
            this.addItemToSuggestions(obj);
        });
    }

    private formatTemporarily_added ()
    {
        var tA = this.temporarily_added;
        //console.log('formatting:');
        //console.log(tA);
        var query = '';
        for(var i = 0; i < tA.length; i++) {
            var pa = tA[i];
            //console.log(pa);
            query += '&temporarily_added[]=' + pa.id;
        }
        //console.log(query);
        return query;
    }

    public setId(id:number) {
        this.activity_id = id;
    }
}

/**
 * Defines the menu/table on the left of the view.
 * @version 0.0.6
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
        var timer = null;
        var that = this
        $('#activities-search').keyup(function () {
            clearTimeout(timer);
            that.search = encodeURIComponent($(this).val());
            timer = setTimeout(function () {
                that.updateTable();
            }, AJAX_DELAY);
        });
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

    private save() {
        var that = this;
        var active = this.content.getId();
        this.saveButton.removeClass('btn-danger');
        this.saveButton.removeClass('btn-success');
        this.saveButton.addClass('btn-warning');
        this.saveButton.html('<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Saving...');
        var data = {
            record_id: active,
            field_id: id
        };
        switch (type) {
            case 'number':
                data['value'] = Util.isString(value) ? parseInt(<string> value) : value;
                break;
            case 'text':
                data['value'] = <string> value;
                break;
            case 'text-multiple':
                data['value'] = <string[]> value;
                break;
            case 'dropdown':
                if (!Util.isString(value)) {
                    data['is_default'] = true;
                }
                data['value'] = value;
                break;
            case 'date':
                data['value'] = Util.toMysqlFormat(Util.parseNumberDate(<string> value));
                break;
            case 'long-text':
                data['value'] = <string> value;
                break;
        }
        AJAX.post(Util.url('post/data'), data, function (response:any) {
            that.saveButton.removeClass('btn-warning');
            that.saveButton.addClass('btn-success');
            that.saveButton.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Changes saved.');
        }, function (message:string) {
            that.saveButton.removeClass('btn-warning');
            that.saveButton.addClass('btn-danger');
            that.saveButton.html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Saving failed.');
            Util.error('An error has occurred during the process of updating of the data. Error message: ' + message);
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
     * Sets up the pagination
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
     * Successively adds the parameters to one row and adds it to the DOM
     * @param data
     */
    private addRowToTable(data:ShortActivityData, active:boolean) {
        var row:JQuery;
        var startD;
        var endD;
        var that = this;
        startD = Util.formatShortDate(Util.parseSQLDate(<string> data.start_date));
        endD = Util.formatShortDate(Util.parseSQLDate(<string> data.end_date));
        row = $('<tr></tr>');
        if(active){
            row.addClass('active');
        }
        row.append('<td>' + Util.shortify(data.name, 20) + '</td>');
        row.append('<td>' + startD + '-' + endD + '</td>');
        row.click(function() {
            that.displayActivity.call(null, data.id);
        });
        row.addClass('noselect');
        this.table.append(row);
    }

    private displayActivity(activityId:string) {
        Util.to('/activity/view/' + activityId);
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
    private onPage:number;
    private id:number;
    private activeTargetGroup:number;
    private people:ParticipantData[];
    private addedPeople:ParticipantData[] = [];
    private removedPeople:ParticipantData[] = [];
    private existingPeople:PeopleField;

    public getId() {
        return this.id;
    }

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
            that.activeTargetGroup = NaN;
            that.setUp();
            that.existingPeople.load(id);
            that.makeLinkWithSuggestions();
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
            that.activeTargetGroup = data.current_target_group;
            that.people = data.participants;
            that.onPage = data.page;
            that.displayTitle(data.name);
            that.displayPeople();
            that.displayTargetGroup(data.target_group);
            that.displayComment(data.target_group_comment);
            that.displayStartDate(data.start_date);
            that.displayEndDate(data.end_date);
        }, function (message:string) {
            Util.error('An error has occurred while loading the list of activities. Please reload the page or contact the administrator. Error message: ' + message);
        });
    }

    /**
     * Displays the title of the activity in the dedicated textfield
     * @param title
     */
    private displayTitle(title:string){
        $('#activity-title').val(title);
    }

    private makeLinkWithSuggestions(){
        var ta = $('.twitter-typeahead');
        ta.keyup(function(e){
            if(e.which == 13) {
                $('.tt-suggestion:first-child', this).trigger('click');
            }
        });
        ta.on('typeahead:selected', {that: this}, addItemFromSuggestion);
    }

    /**
     * Shows the target group as dropdown
     * @param options
     * @param active
     */
    private displayTargetGroup(options:string[]){
        var dropD = $('#target-dropdown');
        dropD.append('<li class="dropdown-header">Choose a target group:</li>');
        var bt = $('#target-button');
        bt.append(options[this.activeTargetGroup] + ' <span class="caret"></span>');
        for(var i = 0; i < options.length; i++) {
            var option = $('<li optionNameUnique="' + i + '"><a>' + options[i] + '</a></li>');
            var that = this;
            if(i == this.activeTargetGroup) {
                option.addClass('disabled');
            } else {
                option.click(function() {
                    that.activeTargetGroup = parseInt($(this).attr('optionNameUnique'));
                    dropD.empty();
                    bt.empty();
                    that.displayTargetGroup(options);
                });
                option.addClass('noselect');
            }
            dropD.append(option);
        }
    }

    /*private resetTimer(){
        $.idleTimer(600);
        var that = this;
        $(document).bind("idle.idleTimer", that.save);
        $(document).bind("active.idleTimer", that.resetTimer);
    }

    private save(){
        //console.log("saving...");
        $.idleTimer('destroy');
        this.resetTimer();
    }*/

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
        var startDate = Util.getDatePicker(startD, "start-date-picker");
        $('#start-date').append(startDate);
    }

    /**
     * Displays the end date of the activity
     * @param sqldate
     */
    private displayEndDate(sqldate:string){
        var endD:string = Util.formatNumberDate(Util.parseSQLDate(<string> sqldate));
        var endDate = Util.getDatePicker(endD, "start-date-picker");
        $('#end-date').append(endDate);
    }

    /**
     * Creates the table with all the people in a activity
     * @param people
     */
    private displayPeople(){
        var people = this.people.concat(this.addedPeople);
        this.peopleTable.empty();
        people.sort(sortParticipants);
        for(var i = 0; i < people.length; i++) {
            var person:ParticipantData = people[i];
            this.displayPerson(person);
        }
    }

    private displayPerson(person:ParticipantData) {
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
    }

    private addRemoveClick(button, person:ParticipantData) {
        var that = this;
        function removePerson(e) {
            var c = e.data; //context
            //console.log('removing from activityinfo array added people name ' + c.person.name);
            Util.removeFromArray(c.person, c.that.addedPeople);
            c.that.removedPeople.push(c.person);
            c.that.existingPeople.addItemToSuggestions(c.person);
            //that.removePerson.call(null, person.id);
            c.that.displayPeople();
            c.that.existingPeople.resetBloodhound();
            c.that.makeLinkWithSuggestions();
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

function sortParticipants(a, b) {
    if (a.name > b.name)
        return 1;
    if (b.name > a.name)
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
    if(Util.isIn(item, c.that.removedPeople)){
        Util.removeFromArray(item, c.that.removedPeople);
    } else {
        c.that.addedPeople.push(item);
    }
    c.that.existingPeople.removeItemFromSuggestions(item);
    c.that.displayPeople();
    c.that.existingPeople.resetBloodhound();
    c.that.makeLinkWithSuggestions();
}

$(document).ready(function () {
    var id = Util.extractId(window.location.toString());
    var hidden = $('input[name="hiddenField"]');
    var page = hidden.val();
    console.log(page);
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

