<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;
use Apollo\Components\View;
use Apollo\Helpers\URLHelper;


/**
 * Class RecordController
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.4
 */
class RecordController extends GenericController
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
            ['Records', URLHelper::url('record'), true]
        ];
        View::render('record.index', 'Records', $breadcrumbs);
    }

    /**
     * Shows one particular record
     *
     * @since 0.0.4 Changed filenames
     * @since 0.0.3
     */
    public function actionView($personId)
    {
        $breadcrumbs = [
            ['Records', URLHelper::url('record/'), true]
        ];
        View::render('record.single', 'View Record', $breadcrumbs);
    }
}