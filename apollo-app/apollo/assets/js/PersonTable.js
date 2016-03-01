/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.2
 */


/**
 * Responsible for one row only
 * @since 0.0.1
 */

var PersonRow = React.createClass({
    render: function() {
        return (
            <tr onClick={this.handleClick}>
                <td>{this.props.firstname}</td>
                <td>{this.props.lastname}</td>
                <td>{this.props.email}</td>
                <td>{this.props.phone}</td>
            </tr>
        );
    },
    handleClick: function(event) {
        window.location.href='records/' + this.props.id;
    }
});
/**
 * Responsible for creating one table of people
 * @since 0.0.2 Changed class to className to fix CSS
 * @since 0.0.1
 */
var PersonTable = React.createClass({
    render: function() {
        console.log(this.props.url);
        var rows = [];
        if (this.state.error != null) {
            console.error(this.state.error.id + ": " + this.state.error.description);
        } else if (this.state.data == null) {
            console.error("Product did not get any data.");
        } else if (this.state.data.data == null) {
            console.error("Although successfully received JSON, data not defined");
        } else {
            if (this.state.data.data != null) {
                for (var i = 0; i < this.state.data.data.length; i++) {
                    var product = this.state.data.data[i];
                    if (product == null) {
                        console.error("Product unexpectedly not found");
                    }
                    rows.push(
                        <PersonRow
                            firstname={product.given_name}
                            lastname={product.last_name}
                            email={product.email}
                            phone={product.phone}
                            id={product.id}
                        />
                    );
                }
            }
        }
        return (
            <table className="table table-striped table-hover">
                <thead>
                <tr>
                    <th>First name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody id="table-body">
                {rows}
                </tbody>
            </table>
        );
    },
    loadPeopleFromServer: function() {
        console.log(this.props.url);
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                this.setState({data: data});
                console.log(this.state.data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getInitialState: function() {
        return {data: []};
    },
    componentDidMount: function() {
        this.loadPeopleFromServer();
        setInterval(this.loadPeopleFromServer, this.props.pollInterval);
    }
});


/**
 * Responsible for handling the pagination and passing the data to the "dumb" PersonTable
 * @since 0.0.1
 * TODO Chris: add API integration,
 */
ReactDOM.render(
    <PersonTable pollInterval={2000} url="/api/get/records/1/"/>,
document.getElementById('personTable')
);