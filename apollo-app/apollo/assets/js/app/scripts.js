///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.1.5
 */
/**
 * Constant specifying a delay before the AJAX request after the user
 * has finished typing
 * @since 0.1.4
 */
var AJAX_DELAY = 600;
/**
 * Util class
 * @since 0.0.4
 */
var Util = (function () {
    function Util() {
    }
    /**
     * Returns the full URL to resource
     *
     * @returns {string}
     * @since 0.0.4
     */
    Util.url = function (url, trailingSlash) {
        if (trailingSlash === void 0) { trailingSlash = true; }
        if (url.substr(0, 1) != '/') {
            url = '/' + url;
        }
        if (trailingSlash && url.substr(url.length - 1) != '/') {
            url += '/';
        }
        return url;
    };
    /**
     * Sends the user to specified URL
     *
     * @param url
     * @param trailingSlash
     * @since 0.1.0
     */
    Util.to = function (url, trailingSlash) {
        if (trailingSlash === void 0) { trailingSlash = true; }
        location.href = Util.url(url, trailingSlash);
    };
    /**
     * Displays the error modal window
     *
     * @param message
     * @since 0.0.4
     */
    Util.error = function (message) {
        if (message === void 0) { message = 'An error has occurred.'; }
        var modal = $('#error-modal');
        var messageContainer = $('#error-message');
        modal.on('show.bs.modal', function () {
            messageContainer.html(message);
        });
        modal.modal('show');
    };
    /**
     * Converts MySQL date time string into JS' Date object
     *
     * @param sqlDate
     * @returns {Date}
     * @since 0.0.6
     */
    Util.parseSQLDate = function (sqlDate) {
        var parts = sqlDate.split(/[- :]/);
        return new Date(+parts[0], +parts[1] - 1, +parts[2], +parts[3], +parts[4], +parts[5]);
    };
    /**
     * Creates a MySQL date string based on JS Date object
     *
     * @param date
     * @returns {string}
     * @since 0.1.2
     */
    Util.toMysqlFormat = function (date) {
        return date.getUTCFullYear() + "-" + Util.twoDigits(1 + date.getUTCMonth()) + "-" + Util.twoDigits(date.getUTCDate()) + " " + Util.twoDigits(date.getUTCHours()) + ":" + Util.twoDigits(date.getUTCMinutes()) + ":" + Util.twoDigits(date.getUTCSeconds());
    };
    ;
    /**
     * Required for the function above
     *
     * @param d
     * @returns {string}
     * @since 0.1.2
     */
    Util.twoDigits = function (d) {
        if (0 <= d && d < 10)
            return "0" + d.toString();
        if (-10 < d && d < 0)
            return "-0" + (-1 * d).toString();
        return d.toString();
    };
    /**
     * Formats JS Date to the following format:
     * January 1st, 1970
     *
     * @param date
     * @returns {string}
     * @since 0.0.7
     */
    Util.formatDate = function (date) {
        var months = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];
        var day = date.getDate().toString().slice(-1);
        var daySuffix = 'th';
        if (day == '1')
            daySuffix = 'st';
        if (day == '2')
            daySuffix = 'nd';
        if (day == '3')
            daySuffix = 'rd';
        return months[date.getMonth()] + ' ' + date.getDate() + daySuffix + ', ' + date.getFullYear();
    };
    /**
     * Formats JS Date to the following format:
     * January 1st, 1970
     *
     * @param date
     * @returns {string}
     * @since 0.1.1
     */
    Util.formatShortDate = function (date) {
        var months = [
            "Jan", "Feb", "Mar",
            "Apr", "May", "Jun", "Jul",
            "Aug", "Sep", "Oct",
            "Nov", "Dec"
        ];
        return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
    };
    /**
     * Gets a date picker with specified value and div id
     *
     * @param date
     * @param divid
     * @returns {JQuery}
     * @since 0.1.5
     */
    Util.getDatePicker = function (date, divid) {
        var inputField = $('<input id="' + divid + '" type="text" value="' + date + '" class="form-control input-sm input-block-level">');
        var container = $('<div class="input-group date" data-provide="datepicker"></div>');
        var content = $('<span class="input-group-addon" style="padding: 0 18px !important; font-size: 0.8em !important;"><i class="glyphicon glyphicon-th"></i></span>');
        var assembled = container.append(inputField);
        assembled.append(content);
        return container;
    };
    /**
     * Extracts the ID (of a record) from the URL, i.e.
     * ../record/view/201/ -> 201
     *
     * @param url
     * @returns {number}
     * @since 0.1.3
     */
    Util.extractId = function (url) {
        var re = new RegExp("[^\/]+(?=\/*$)|$");
        var base = re.exec(url);
        if (base == null)
            return NaN;
        return parseInt(base[0]);
    };
    /**
     * Builds a JQuery node
     *
     * @param tag
     * @param attributes
     * @param content
     * @param selfClosing
     * @returns {JQuery}
     * @since 0.1.4
     */
    Util.buildNode = function (tag, attributes, content, selfClosing) {
        if (attributes === void 0) { attributes = {}; }
        if (content === void 0) { content = ''; }
        if (selfClosing === void 0) { selfClosing = false; }
        var attributesString = '';
        for (var key in attributes) {
            if (attributes.hasOwnProperty(key)) {
                attributesString += ' ' + key + '="' + attributes[key].replace('"', '\\"') + '"';
            }
        }
        return $('<' + tag + attributesString + (selfClosing ? ' />' : '>' + content + '</' + tag + '>'));
    };
    /**
     * Merges two objects into one
     *
     * @param object1
     * @param object2
     * @returns {{}}
     * @since 0.1.4
     */
    Util.mergeObjects = function (object1, object2) {
        var object = {};
        for (var key in object1) {
            if (object1.hasOwnProperty(key)) {
                object[key] = object1[key];
            }
        }
        for (var key in object2) {
            if (object2.hasOwnProperty(key)) {
                object[key] = object2[key];
            }
        }
        return object;
    };
    return Util;
})();
/**
 * Deals with loaders
 * @since 0.0.9 Added documnetation
 * @since 0.0.5
 */
var LoaderManager = (function () {
    function LoaderManager() {
    }
    /**
     * Returns the unique ID of the loader after placing it on the page as the first child of the target
     * container. The loader is initially hidden.
     *
     * @param target
     * @returns {number}
     * @since 0.0.5
     */
    LoaderManager.createLoader = function (target) {
        var loader = $('<div class="loader"></div>');
        for (var i = 0; i < 5; i++) {
            loader.append($('<div class="line-' + (i + 1) + '"></div>'));
        }
        var id = LoaderManager.newId();
        var container = $('<div class="loader-container loader-' + id + '" style="display: none">');
        container.append(loader);
        this.loaders[id] = container;
        target.prepend(container);
        return id;
    };
    /**
     * Fades in the loader, callback is called after the animation is complete
     *
     * @param id
     * @param callback
     * @since 0.0.5
     */
    LoaderManager.showLoader = function (id, callback) {
        if (callback === void 0) { callback = null; }
        if (callback == null) {
            this.loaders[id].fadeIn(200);
        }
        else {
            this.loaders[id].fadeIn(200, callback);
        }
    };
    /**
     * Fades out the loader, callback is called after the animation is complete
     *
     * @param id
     * @param callback
     * @since 0.0.5
     */
    LoaderManager.hideLoader = function (id, callback) {
        if (callback === void 0) { callback = null; }
        if (callback == null) {
            this.loaders[id].fadeOut(200);
        }
        else {
            this.loaders[id].fadeOut(200, callback);
        }
    };
    /**
     * Removes the loader from the page based on its ID
     *
     * @param id
     */
    LoaderManager.destroyLoader = function (id) {
        this.loaders[id].remove();
        delete this.loaders[id];
    };
    LoaderManager.newId = function () {
        return ++this.counter;
    };
    LoaderManager.loaders = {};
    LoaderManager.counter = 0;
    return LoaderManager;
})();
/**
 * Sets the base url
 * @since 0.0.1
 */
if (!location.origin) {
    location.origin = location.protocol + "//" + location.host;
}
