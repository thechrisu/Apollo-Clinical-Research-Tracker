///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.5
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
    public static url(url:string, trailingSlash:boolean = true) {
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
}

/**
 * Deals with loaders
 * @since 0.0.5
 */
class LoaderManager {

    private static loaders:{[id: number] : JQuery};
    private static counter = 0;

    public static createLoader(target:JQuery) {
        var loader = $('<div class="loader"></div>');
        for(var i = 0; i < 5; i++) {
            loader.append($('<div class="line-' + (i + 1) +'"></div>'));
        }
        var id = LoaderManager.newId();
        var container = $('<div class="loader-container loader-' + id + '" style="display: none">');
        this.loaders[id] = container;
        target.prepend(container);
    }

    public static showLoader(id: number) {
        this.loaders[id].fadeIn(200);
    }

    public static hideLoader(id: number) {
        this.loaders[id].fadeOut(200);
    }

    public static destroyLoader(id: number) {
        this.loaders[id].remove();
        delete this.loaders[id];
    }

    private static newId() : number {
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

/**
 * Returns the full URL to resource
 *
 * @returns {string}
 * @since 0.0.2
 * @deprecated
 */
function url(url:string, trailingSlash:boolean = true) {
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
 * @since 0.0.2
 * @deprecated
 */
function error(message:string = 'An error has occurred.') {
    var modal = $('#error-modal');
    var messageContainer = $('#error-message');
    modal.on('show.bs.modal', function () {
        messageContainer.html(message);
    });
    modal.modal('show');
}