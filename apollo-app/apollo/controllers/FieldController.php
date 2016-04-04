<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\View;
use Apollo\Helpers\URLHelper;


/**
 * Class FieldController
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class FieldController extends GenericController
{
    /**
     * Shows the list of all records
     *
     * @since 0.0.4 Changed filenames
     * @since 0.0.2 Now using the View::render() shorthand
     * @since 0.0.1
     */
    public function index()
    {
        $breadcrumbs = [
            ['Field', URLHelper::url('field'), true]
        ];
        View::render('field.index', 'Field', $breadcrumbs);
    }
}