///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.2.2
 */
/**
 * Constant specifying a delay before the AJAX request after the user
 * has finished typing
 * @since 0.1.9 Added AJAX_LAZY_DELAY for cases when instant updates are not required
 * @since 0.1.4
 */
var AJAX_DELAY = 600;
var AJAX_LAZY_DELAY = 1000;
/**
 * Constants specifying the IDs of the fields recognised by the backend
 * @since 0.2.1 Added awards and publications
 * @since 0.1.6
 */
var FIELD_GIVEN_NAME = -1;
var FIELD_MIDDLE_NAME = -2;
var FIELD_LAST_NAME = -3;
var FIELD_RECORD_NAME = 1;
var FIELD_START_DATE = 2;
var FIELD_END_DATE = 3;
var FIELD_EMAIL = 4;
var FIELD_PHONE = 5;
var FIELD_ADDRESS = 6;
var FIELD_AWARDS = 7;
var FIELD_PUBLICATIONS = 8;
/**
 * Util class
 * @since 0.0.5
 */
var Util = (function () {
    function Util() {
    }
    Util.getOuterHTML = function (elem) {
        return $('<div />').append(elem.eq(0).clone()).html();
    };
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
    Util.escapeHTML = function (unsafe) {
        var cast = '';
        if ((typeof unsafe).localeCompare("string") == 0)
            cast = unsafe;
        else
            cast = unsafe; //unsafe.toString();
        return cast
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
        //return _.escape(midsafe);
        //return midsafe;
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
     * Removes all instances of an element from another array
     * @param toSubtractFrom
     * @param subtractedBy
     * @returns {any[]}
     */
    Util.arraySubtract = function (toSubtractFrom, subtractedBy) {
        return toSubtractFrom.filter(function (item) {
            return subtractedBy.indexOf(item) === -1;
        });
    };
    /**
     * Removes all instances of an item in an array from another array. Comparison is done with a custom compare function
     * @param toSubtractFrom
     * @param subtractedBy
     * @param compareFunction
     */
    Util.arraySubtractCmp = function (toSubtractFrom, subtractedBy, compareFunction) {
        for (var i = 0; i < subtractedBy.length; i++) {
            this.removeFromArrayCmp(subtractedBy[i], toSubtractFrom, compareFunction);
        }
    };
    /**
     * Removes all instances of an item from an array
     * @param needle
     * @param haystack
     */
    Util.removeFromArray = function (needle, haystack) {
        var index = haystack.indexOf(needle);
        if (index > -1)
            haystack.splice(index, 1);
    };
    /**
     * Removes all instances of an item from an array using a custom compare function
     * @param needle
     * @param haystack
     * @param compareFunction
     */
    Util.removeFromArrayCmp = function (needle, haystack, compareFunction) {
        for (var i = 0; i < haystack.length; i++) {
            if (compareFunction(haystack[i], needle) == 0) {
                haystack.splice(i, 1);
                i--; //decrement since the array indices will move up
            }
        }
    };
    /**
     * Checks if an element is in an array
     * @param needle
     * @param haystack
     * @returns {boolean}
     */
    Util.isIn = function (needle, haystack) {
        return haystack.indexOf(needle) > -1;
    };
    /**
     * Checks if an element is in an array using a custom compare function
     * @param needle
     * @param haystack
     * @param compareFunction
     * @returns {boolean}
     */
    Util.isInCmp = function (needle, haystack, compareFunction) {
        for (var i = 0; i < haystack.length; i++) {
            var item = haystack[i];
            if (compareFunction(needle, item) == 0) {
                return true;
            }
        }
        return false;
    };
    /**
     * If a string is larger than a given size, it will shorten the string and replace the last three characters with dots
     * @param str
     * @param maxLength
     * @returns {string}
     */
    Util.shortify = function (str, maxLength) {
        var res = str;
        if (str.length > maxLength) {
            var spliceLocation = maxLength - 3;
            res = str.substring(0, spliceLocation);
            res = res.slice(0, spliceLocation) + '...';
        }
        return res;
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
     * Converts number date time string into JS' Date object
     * Initial format: dd/mm/yyyy
     *
     * @param numberDate
     * @returns {Date}
     * @since 0.2.0
     */
    Util.parseNumberDate = function (numberDate) {
        var parts = numberDate.split(/\//);
        return new Date(+parts[2], +parts[1] - 1, +parts[0], 0, 0, 0);
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
     *
     * @param date
     * @return {string}
     * @since 0.1.6
     */
    Util.formatNumberDate = function (date) {
        return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
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
    /**
     * Determines whether the supplied object is a string
     *
     * @param object
     * @returns {boolean}
     * @since 0.1.8
     */
    Util.isString = function (object) {
        return typeof object === 'string' || object instanceof String;
    };
    /**
     * Determines whether the supplied object is an array
     *
     * @param object
     * @returns {boolean}
     * @since 0.2.0
     */
    Util.isArray = function (object) {
        return Object.prototype.toString.call(object) === '[object Array]';
    };
    /**
     * Wraps a string in <strong> tags
     *
     * @param value
     * @returns {string}
     * @since 0.1.9
     */
    Util.strong = function (value) {
        return '<strong>' + value + '</strong>';
    };
    return Util;
})();
/**
 * Deals with loaders
 * @since 0.0.9 Added documentation
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
