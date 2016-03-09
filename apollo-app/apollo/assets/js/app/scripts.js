///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.1.1
 */
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
