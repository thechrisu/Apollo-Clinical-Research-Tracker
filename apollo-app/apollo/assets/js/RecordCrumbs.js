/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

/**
 * Composite breadcrumbs component
 * @version 0.0.1
 */
var PersonCrumbs = React.createClass({
    render: function(){
        return(

        );
    },
    loadRecordsFromServer: function() {
    },

    getInitialState: function() {
        return {data: []};
    },
    componentDidMount: function() {
        this.loadRecordsFromServer();
        document.title = this.state.data.given_name + ' ' + this.state.data.last_name + ' | Record #' + this.state.data.id;
    },
    componentDidMount: function(){
    }
});

/**
 * Renders the updated breadcrumbs
 * @version 0.0.1
 */
ReactDOM.render(
    <PersonCrumbs />,
    document.getElementById('nav-breadcrumbs')
);