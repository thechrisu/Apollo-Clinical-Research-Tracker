/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

import React, { Component, PropTypes } from 'react';

export default class Pagination extends Component {
    static proptypes = {
        pageNum: PropTypes.number.isRequired,
        pageRangeDisplayed: PropTypes.number.isRequired,
        initialSelected
    }
}