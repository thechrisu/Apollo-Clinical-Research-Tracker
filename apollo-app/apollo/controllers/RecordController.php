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
 * Class RecordController
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.8
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
     * @param string $record_id
     * @since 0.0.6 New breadcrumb structure
     * @since 0.0.5 Added a check for record ID validity
     * @since 0.0.4 Changed file names
     * @since 0.0.3
     */
    public function actionView($record_id = null)
    {
        $record_id = intval($record_id);
        if($record_id < 1) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid record ID!');
        }
        $breadcrumbs = [
            ['Records', URLHelper::url('record'), true],
            ['Person name', null, false],
            ['Record name (Record ID)', null, true]
        ];
        View::render('record.single', 'View Record', $breadcrumbs);
    }

    /**
     * Renders the edit view for a particular record
     *
     * @param string $record_id
     * @since 0.0.5 Added a check for record ID validity
     */
    public function actionEdit($record_id = null)
    {
        $record_id = intval($record_id);
        if($record_id < 1) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid record ID!');
        }
        $breadcrumbs = [
            ['Records', URLHelper::url('record'), true],
            ['Person name', null, false],
            ['Record name (Record ID)', null, true]
        ];
        View::render('record.edit', 'Edit Record', $breadcrumbs);
    }

    /**
     * Action to deal with advanced search
     *
     * TODO: Complete this
     *
     * @since 0.0.8
     */
    public function actionSearch()
    {

    }
}