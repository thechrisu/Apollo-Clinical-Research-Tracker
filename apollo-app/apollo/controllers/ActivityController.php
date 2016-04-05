<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Controllers;


use Apollo\Apollo;
use Apollo\Components\Activity;
use Apollo\Components\View;
use Apollo\Helpers\URLHelper;


/**
 * Class ActivityController
 *
 * @package Apollo\Controllers
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 */
class ActivityController extends GenericController
{
    /**
     * Shows all activities
     *
     * @since 0.0.1
     */
    public function index()
    {
        $id = Activity::getMinId();
        $breadcrumbs = [
            ['Activities', URLHelper::url('activity/view/' . $id), true],
            ['Activity name (Activity ID)', null, true]
        ];
        View::render('activity.activity', 'Activities', $breadcrumbs);
    }

    /**
     * Shows one particular activity
     * @param null $activity_id
     */
    public function actionView($activity_id = null)
    {
        $activity_id = intval($activity_id);
        if($activity_id < 1) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid activity ID!');
        } else {
            $breadcrumbs = [
                ['Activities', URLHelper::url('activity/view/' . $activity_id), true],
                ['Activity name (Activity ID)', null, true],

            ];
            View::render('activity.activity', 'View Activity', $breadcrumbs);
        }
    }
}