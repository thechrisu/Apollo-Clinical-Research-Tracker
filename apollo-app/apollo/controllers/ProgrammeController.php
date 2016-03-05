<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Controllers;


use Apollo\Apollo;
use Apollo\Components\View;
use Apollo\Helpers\URLHelper;


/**
 * Class ProgrammeController
 *
 * @package Apollo\Controllers
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 */
class ProgrammeController extends GenericController
{
    /**
     * Shows all programmes
     *
     * @since 0.0.1
     */
    public function index()
    {
        $breadcrumbs = [
            ['Programmes', URLHelper::url('programme'), true]
        ];
        View::render('programme.programme', 'Programmes', $breadcrumbs);
    }
}