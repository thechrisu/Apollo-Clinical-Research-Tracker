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
/**
 * Performs a GET request to the specified URL, executes the specified functions
 * @param url
 * @param successCallback
 * @param errorCallback
 * @deprecated
 */
function ajaxGet(url, successCallback, errorCallback) {
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
}
/**
 * Mimics a GET request, with the data already provided as a function argument. The idea is to provide a quick way of
 * testing views with dummy data if the API is not ready yet.
 * Note that his function requires an "error" object set to null to execute the successCallback.
 * Also note that this function should never be called in production code
 * @param data
 * @param successCallback
 * @param errorCallback
 * @deprecated
 */
function fakeajaxGet(data, successCallback, errorCallback) {
    //var data = JSON.parse(toDecode);
    call(data);
    function call(data) {
        if (data.error == null) {
            successCallback(data);
        }
        else {
            errorCallback(data.error.description);
        }
    }
}
