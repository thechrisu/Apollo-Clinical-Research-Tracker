/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 * TODO fix CSS
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
 * @since 0.0.1
 */
var PersonTable = React.createClass({
    render: function() {
        var rows = [];
        this.props.data.forEach(function(product) {
            if(product.error == null && product.data != null)
            {
                rows.push(
                    <PersonRow
                        firstname={product.data.firstname}
                        lastname={product.data.lastname}
                        email={product.data.email}
                        phone={product.data.phone}
                        id={product.data.id}
                    />
                );
            }
            if(product.error != null)
                console.log(product.error.id + ": " + product.error.description);
            if(product.data == null)
                console.log("Product did not get any data.");

        }.bind(this));
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
    }
});


/**
 * Responsible for handling the pagination and passing the data to the "dumb" PersonTable
 * @since 0.0.1
 * TODO add API integration, add real pagination (with nextpage/lastpage), make it more robust
 */
var PersonTablePagination = React.createClass({
    render: function() {
        return (
            <PersonTable data={this.state.data} />
        )
    },
    loadPeopleFromServer: function() {
        this.setState({data: [
            {
                "error": null,
                "data": {
                    "firstname": "Peter",
                    "lastname": "Parker",
                    "email": "spider@man.com",
                    "phone": "+1 23456789",
                    "id": 532
                }
            },
            {
                "error": null,
                "data": {
                    "firstname": "Christoph",
                    "lastname": "Ulshoefer",
                    "email": "christophsulshoefer@gmail.com",
                    "phone": "0133723666",
                    "id": 1337
                }
            },
            {
                "error": null,
                "data": {
                    "firstname": "Dummy",
                    "lastname": "Person",
                    "email": "lorem@ipsum.com",
                    "phone": "+4 5125218051",
                    "id": 7001
                }
            }
        ]})
    },
    getInitialState() {
        return {data: []};
    },
    componentDidMount() {
        this.loadPeopleFromServer();
        setInterval(this.loadPeopleFromServer, this.props.pollInterval);
    }
});

ReactDOM.render(
    <PersonTablePagination pollInterval={2000} />,
document.getElementById('personTable')
);