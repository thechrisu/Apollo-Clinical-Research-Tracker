/**
 * Performs a GET request to the specified URL, executes the specified functions
 * @param url
 * @param successCallback
 * @param errorCallback
 */
function ajaxGet(url: string, successCallback: Function, errorCallback: Function) {
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        success: function(data) {
            if(data.error != null) {
                errorCallback(data.error.description);
            } else {
                successCallback(data);
            }
        }.bind(this),
        error: function(xhr, status, err) {
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
 */
function fakeajaxGet(data: JSON, successCallback: Function, errorCallback: Function) {
    //var data = JSON.parse(toDecode);
    call(data);

    function call(data) {
        if(data.error == null) {
            successCallback(data);
        } else {
            errorCallback(data.error.description);
        }
    }
}