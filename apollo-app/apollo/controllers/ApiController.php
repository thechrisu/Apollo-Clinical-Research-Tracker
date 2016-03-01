<?php
/**
 * @author Desislava Koleva <desy.koleva96@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Apollo\Controllers;


use Apollo\Apollo;

/**
 * Class ApiController
 *
 * API to server JSON on get and parse data on post requests.
 *
 * @package Apollo\Controllers
 * @author Desislava Koleva <desy.koleva96@gmail.com>
 * @version 0.0.1
 */
class ApiController extends GenericController
{
    /**
     * Default action
     * @since 0.0.1
     */
    public function index()
    {
        Apollo::getInstance()->getRequest()->error(400, 'No action has been specified.');
    }

    /**
     * Not found action
     * @since 0.0.1
     */
    public function notFound()
    {
        $request = Apollo::getInstance()->getRequest();
        $request->error(400, 'Requested action does not exist.');
    }

    /**
     * Action to handle get requests
     * @since 0.0.1
     */
    public function actionGet() // url/api/get/
    {
        $request = Apollo::getInstance()->getRequest();
        $parameters = $request->getParameters();
        if (count($parameters) == 0) {
            $request->error(400, 'No parameters were specified.');
        } else {
            switch ($parameters[0]) {
                case 'records':
                    getRecords();
                    break;
                case 'fields':
                    getFields();
                    break;
                case 'programs':
                    getPrograms();
                    break;
                case 'program':
                    getProgram();
                    break;
                case 'awards':
                    getAwards();
                    break;
                case 'publications':
                    getPublications();
                    break;
                default:
                    $request->error(400, 'Invalid first parameter.');
            }
        }
    }

    public function getRecords()
    {

    }

    public function getFields()
    {

    }

    public function getPrograms()
    {

    }

    public function getProgram()
    {

    }

    public function getAwards()
    {

    }

    public function getPublications()
    {

    }
}