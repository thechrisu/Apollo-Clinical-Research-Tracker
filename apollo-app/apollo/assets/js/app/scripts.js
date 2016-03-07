///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.8
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
    return Util;
})();
/**
 * Deals with loaders
 * @since 0.0.5
 */
var LoaderManager = (function () {
    function LoaderManager() {
    }
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
    LoaderManager.showLoader = function (id, callback) {
        if (callback === void 0) { callback = null; }
        if (callback == null) {
            this.loaders[id].fadeIn(200);
        }
        else {
            this.loaders[id].fadeIn(200, callback);
        }
    };
    LoaderManager.hideLoader = function (id, callback) {
        if (callback === void 0) { callback = null; }
        if (callback == null) {
            this.loaders[id].fadeOut(200);
        }
        else {
            this.loaders[id].fadeOut(200, callback);
        }
    };
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
