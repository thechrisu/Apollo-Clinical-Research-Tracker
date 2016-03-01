/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */


/**
 * Composite component for the whole record
 * @version 0.0.1
 */
var Record = React.createClass({
    render: function(){
        return(
            <div>
                <BasicInfo />
                <FieldCollection />
            </div>
        );
    },
    loadRecordsFromServer: function() {
        console.log("Doing GET request");
        this.setState({ data: [
                    {'id': 531},
                    {'given_name': 'Charlotte'},
                    {'last_name': 'Warren-Gash'}
                ]
            }
        );
    },

    getInitialState: function() {
        return {data: []};
    },
    componentDidMount: function() {
        this.loadRecordsFromServer();
        document.title = this.state.data.given_name + ' ' + this.state.data.last_name + ' | Record #' + this.state.data.id;
    }
});

/**
 * Responsible for the basic information of a person: Address, Name, ...
 * @version 0.0.1
 */
var BasicInfo = React.createClass({
    render: function(){
        return(
            <div className="row">
                <div className="col-md-3">
                    <img src="images/record.png" className="img-thumbnail" />
                </div>
                <div className="col-md-5">
                    <h3>Charlotte
                        <small>First Name</small>
                    </h3>
                    <h3>Warren-Gash
                        <small>Surname</small>
                    </h3>
                    <h3>c.warren-gash@ucl.ac.uk
                        <small>Email</small>
                    </h3>
                    <h3>07894 664 278
                        <small>Phone</small>
                    </h3>
                </div>
                <div className="col-md-4">
                    <h3>
                        <small>Address:</small>
                    </h3>
                    <h3>Institute of Child Health</h3>
                    <h3>UCL, Gower Street</h3>
                    <h3>WC1E 6BT, London, UK</h3>
                </div>
            </div>
            );
        }
});

/**
 * Responsible for displaying all of the fields.
 * @version 0.0.1
 */
var FieldCollection = React.createClass({
    render: function(){
        return (
        <div className="row top-buffer">

            <div className="col-md-4">

                <div className="table-responsibe">

                    <table className="table">

                        <caption>Funding</caption>

                        <tbody>

                        <tr>
                            <td>Funding Source:</td>
                            <td>Deanery</td>
                        </tr>

                        <tr>
                            <td>Funding Category:</td>
                            <td>Deanery</td>
                        </tr>

                        <tr>
                            <td>Pay Band:</td>
                            <td>9</td>
                        </tr>

                        </tbody>

                    </table>

                </div>

                <div className="table-responsibe">

                    <table className="table">

                        <caption>Speciality</caption>

                        <tbody>

                        <tr>
                            <td>Clinical Speciality:</td>
                            <td>Public Health Medicine</td>
                        </tr>

                        <tr>
                            <td>HRCS Health Category:</td>
                            <td>Population Health</td>
                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <div className="col-md-4">

                <div className="table-responsibe">

                    <table className="table">

                        <caption>Research Activity</caption>

                        <tbody>

                        <tr>
                            <td>HRCS Research Activity Codes:</td>
                            <td>Epidemiology</td>
                        </tr>

                        <tr>
                            <td>Research Area:</td>
                            <td>Epidemiology</td>
                        </tr>

                        <tr>
                            <td>Start Date:</td>
                            <td>April 1st, 2014</td>
                        </tr>

                        <tr>
                            <td>End Date:</td>
                            <td>March 31st, 2018</td>
                        </tr>

                        <tr>
                            <td>Supervisor:</td>
                            <td>Professor Geraint Rees</td>
                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <div className="col-md-4">

                <div className="table-responsibe">

                    <table className="table">

                        <caption>Other Info</caption>

                        <tbody>

                        <tr>
                            <td>NHS Trust</td>
                            <td>GOSH</td>
                        </tr>

                        <tr>
                            <td>Next Destination:</td>
                            <td className="secondary-text">Unknown</td>
                        </tr>

                        <tr>
                            <td>Previous Post:</td>
                            <td>Clinical Research Training Fellow</td>
                        </tr>

                        <tr>
                            <td>Highest Degree Type:</td>
                            <td>PhD</td>
                        </tr>

                        <tr>
                            <td>PhD Title:</td>
                            <td>Some PhD Title</td>
                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <div className="row top-buffer">

                <div className="col-md-4">
                    <div className="table-responsive">
                        <table className="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Publications</th>
                            </tr>
                            </thead>
                            <tbody id="table-body-1">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="col-md-4">
                    <div className="table-responsive">
                        <table className="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Awards</th>
                            </tr>
                            </thead>
                            <tbody id="table-body-2">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="col-md-4">
                    <div className="table-responsive">
                        <table className="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Programmes</th>
                            </tr>
                            </thead>
                            <tbody id="table-body-3">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        );
    }
});

 /**
 * Renders the updated breadcrumbs
 * @version 0.0.1
 */
 ReactDOM.render(
    <Record />,
    document.getElementById('recordDetails')
)


