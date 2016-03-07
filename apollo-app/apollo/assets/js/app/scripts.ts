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
 * Default interfaces
 * @since 0.0.3
 */
interface Error {
    id: number,
    description: string
}

/**
 * Util class
 * @since 0.0.4
 */
class Util {

    /**
     * Returns the full URL to resource
     *
     * @returns {string}
     * @since 0.0.4
     */
    public static url(url:string, trailingSlash:boolean = true):string {
        if (url.substr(0, 1) != '/') {
            url = '/' + url;
        }
        if (trailingSlash && url.substr(url.length - 1) != '/') {
            url += '/';
        }
        return url;
    }

    /**
     * Displays the error modal window
     *
     * @param message
     * @since 0.0.4
     */
    public static error(message:string = 'An error has occurred.') {
        var modal = $('#error-modal');
        var messageContainer = $('#error-message');
        modal.on('show.bs.modal', function () {
            messageContainer.html(message);
        });
        modal.modal('show');
    }

    /**
     * Converts MySQL date time string into JS' Date object
     *
     * @param sqlDate
     * @returns {Date}
     * @since 0.0.6
     */
    public static parseSQLDate(sqlDate:string):Date {
        var parts = sqlDate.split(/[- :]/);
        return new Date(+parts[0], +parts[1] - 1, +parts[2], +parts[3], +parts[4], +parts[5]);
    }

    /**
     * Formats JS Date to the following format:
     * January 1st, 1970
     *
     * @param date
     * @returns {string}
     * @since 0.0.7
     */
    public static formatDate(date:Date):string {
        var months = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];
        var day = date.getDate().toString().slice(-1);
        var daySuffix = 'th';
        if (day == '1') daySuffix = 'st';
        if (day == '2') daySuffix = 'nd';
        if (day == '3') daySuffix = 'rd';
        return months[date.getMonth()] + ' ' + date.getDate() + daySuffix + ', ' + date.getFullYear();
    }

}

/**
 * Deals with loaders
 * @since 0.0.5
 */
class LoaderManager {

    private static loaders:{[id: number] : JQuery} = {};
    private static counter = 0;

    public static createLoader(target:JQuery):number {
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
    }

    public static showLoader(id:number, callback:Function = null) {
        if (callback == null) {
            this.loaders[id].fadeIn(200);
        } else {
            this.loaders[id].fadeIn(200, callback);
        }
    }

    public static hideLoader(id:number, callback:Function = null) {
        if (callback == null) {
            this.loaders[id].fadeOut(200);
        } else {
            this.loaders[id].fadeOut(200, callback);
        }
    }

    public static destroyLoader(id:number) {
        this.loaders[id].remove();
        delete this.loaders[id];
    }

    private static newId():number {
        return ++this.counter;
    }

}

/**
 * Sets the base url
 * @since 0.0.1
 */
if (!location.origin) {
    location.origin = location.protocol + "//" + location.host;
}