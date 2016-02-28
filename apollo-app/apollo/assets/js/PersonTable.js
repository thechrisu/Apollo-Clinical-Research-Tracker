/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

import React, { Component } from 'react'

/**
 * Responsible for one row only
 * @since 0.0.1
 */
var PersonRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>{this.props.firstname}</td>
                <td>{this.props.lastname}</td>
                <td>{this.props.email}</td>
                <td>{this.props.phone}</td>
            </tr>
        );
    }
});

/**
 * Responsible for creating one table of people
 * @since 0.0.1
 */
export default class PersonTable extends Component {
    render() {
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
                    />
                );
            }
            if(product.error != null)
                console.log(product.error.id + ": " + product.error.description);
            if(product.data == null)
                console.log("Product did not get any data.");

        }.bind(this));
        return (
            <tbody>
            {rows}
            </tbody>
        );
    }
};