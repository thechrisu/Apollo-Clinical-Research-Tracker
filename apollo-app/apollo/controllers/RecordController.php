<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
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
 * @version 0.0.1
 */
class RecordController extends GenericController
{
    /**
     * Shows the list of all records
     *
     * @since 0.0.1
     */
    public function index()
    {
        $breadcrumbs = [
            ['Records', URLHelper::url('record'), true]
        ];
        View::render('record.records', 'Records', $breadcrumbs);
    }
}