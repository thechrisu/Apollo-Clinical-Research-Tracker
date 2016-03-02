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