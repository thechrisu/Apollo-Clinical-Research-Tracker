///<reference path="jquery.d.ts"/>
/**
 * Scripts file containing functions related to modal windows
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.4
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