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
class AJAX {

    /**
     * Makes an AJAX request and returns the data received to the callback function.
     *
     * @param url
     * @param successCallback
     * @param errorCallback
     * @since 0.0.1
     */
    public static get(url: string, successCallback: Function = null, errorCallback: Function = null) {
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

}