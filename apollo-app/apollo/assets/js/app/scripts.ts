///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.1.3
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
     * Sends the user to specified URL
     *
     * @param url
     * @param trailingSlash
     * @since 0.1.0
     */
    public static to(url:string, trailingSlash:boolean = true) {
        location.href = Util.url(url, trailingSlash);
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
     * Creates a MySQL date string based on JS Date object
     *
     * @param date
     * @returns {string}
     * @since 0.1.2
     */
    public static toMysqlFormat(date:Date):string {
        return date.getUTCFullYear() + "-" + Util.twoDigits(1 + date.getUTCMonth()) + "-" + Util.twoDigits(date.getUTCDate()) + " " + Util.twoDigits(date.getUTCHours()) + ":" + Util.twoDigits(date.getUTCMinutes()) + ":" + Util.twoDigits(date.getUTCSeconds());
    };

    /**
     * Required for the function above
     *
     * @param d
     * @returns {string}
     * @since 0.1.2
     */
    public static twoDigits(d:any):string {
        if (0 <= d && d < 10) return "0" + d.toString();
        if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
        return d.toString();
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

    /**
     * Formats JS Date to the following format:
     * January 1st, 1970
     *
     * @param date
     * @returns {string}
     * @since 0.1.1
     */
    public static formatShortDate(date:Date):string {
        var months = [
            "Jan", "Feb", "Mar",
            "Apr", "May", "Jun", "Jul",
            "Aug", "Sep", "Oct",
            "Nov", "Dec"
        ];
        return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
    }



    /**
     * Partially applies a function: Used for setting "Default" parameters to functions in order to pass them to other objects
     * http://stackoverflow.com/questions/321113/how-can-i-pre-set-arguments-in-javascript-function-call-partial-function-appli
     * @param func
     * @returns {function(): *}
     * @since 0.1.3
     */
    public static partial(func, /*, 0..n args */) {
        var args = Array.prototype.slice.call(arguments, 1);
        return function () {
            var allArguments = args.concat(Array.prototype.slice.call(arguments));
            return func.apply(this, allArguments);
        };
    }
}

/**
 * Deals with loaders
 * @since 0.0.9 Added documnetation
 * @since 0.0.5
 */
class LoaderManager {

    private static loaders:{[id: number] : JQuery} = {};
    private static counter = 0;

    /**
     * Returns the unique ID of the loader after placing it on the page as the first child of the target
     * container. The loader is initially hidden.
     *
     * @param target
     * @returns {number}
     * @since 0.0.5
     */
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

    /**
     * Fades in the loader, callback is called after the animation is complete
     *
     * @param id
     * @param callback
     * @since 0.0.5
     */
    public static showLoader(id:number, callback:Function = null) {
        if (callback == null) {
            this.loaders[id].fadeIn(200);
        } else {
            this.loaders[id].fadeIn(200, callback);
        }
    }

    /**
     * Fades out the loader, callback is called after the animation is complete
     *
     * @param id
     * @param callback
     * @since 0.0.5
     */
    public static hideLoader(id:number, callback:Function = null) {
        if (callback == null) {
            this.loaders[id].fadeOut(200);
        } else {
            this.loaders[id].fadeOut(200, callback);
        }
    }

    /**
     * Removes the loader from the page based on its ID
     *
     * @param id
     */
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