/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */


import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import PersonTable from './PersonTable';

export default class PersonTablePagination extends Component {
    loadPeopleFromServer() {
        this.setState({data: [
            {
                "error": null,
                "data": {
                    "firstname": "Peter",
                    "lastname": "Parker",
                    "email": "spider@man.com",
                    "phone": "+1 23456789"
                }
            },
            {
                "error": null,
                "data": {
                    "firstname": "Christoph",
                    "lastname": "Ulshoefer",
                    "email": "christophsulshoefer@gmail.com",
                    "phone": "0133723666"
                }
            },
            {
                "error": null,
                "data": {
                    "firstname": "Dummy",
                    "lastname": "Person",
                    "email": "lorem@ipsum.com",
                    "phone": "+4 5125218051"
                }
            }
        ]})
    };
    getInitialState() {
        return {data: []};
    };
    componentDidMount() {
        this.loadPeopleFromServer();
        setInterval(this.loadPeopleFromServer, this.props.pollInterval);
    };

};

ReactDOM.render(
    <PersonTablePagination page={} perPage={10} pollInterval={2000} />
document.getElementById('table-body')
);