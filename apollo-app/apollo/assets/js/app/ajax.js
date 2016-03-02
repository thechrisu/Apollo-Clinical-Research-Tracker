/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Class AJAX
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
var AJAX = (function () {
    function AJAX() {
    }
    /**
     * Makes an AJAX request and returns the data received to the callback function.
     *
     * @param url
     * @param successCallback
     * @param errorCallback
     * @since 0.0.1
     */
    AJAX.get = function (url, successCallback, errorCallback) {
        if (successCallback === void 0) { successCallback = null; }
        if (errorCallback === void 0) { errorCallback = null; }
        $.ajax({
            url: url,
            dataType: 'json',
            type: 'GET',
            success: function (data) {
                if (data.error != null) {
                    errorCallback(data.error.description);
                }
                else {
                    successCallback(data);
                }
            }.bind(this),
            error: function (xhr, status, err) {
                errorCallback(err.toString());
            }.bind(this)
        });
    };
    return AJAX;
})();
