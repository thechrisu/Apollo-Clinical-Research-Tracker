<?php
/**
 * @author Desislava Koleva <desy.koleva96@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Apollo\Controllers;


use Apollo\Apollo;
use Apollo\Components\DB;

/**
 * Class ApiController
 *
 * API to server JSON on get and parse data on post requests.
 *
 * @package Apollo\Controllers
 * @author Desislava Koleva <desy.koleva96@gmail.com>
 * @version 0.0.2
 */
class ApiController extends GenericController
{
    /**
     * Since an empty request to API is not valid, return 400 error
     *
     * @since 0.0.1
     */
    public function index()
    {
        Apollo::getInstance()->getRequest()->error(400, 'No action has been specified.');
    }

    /**
     * Request to a non-existent action is invalid, return 400
     *
     * @since 0.0.1
     */
    public function notFound()
    {
        Apollo::getInstance()->getRequest()->error(400, 'Requested action does not exist.');
    }

    /**
     * Action to handle get requests
     *
     * @param string $table
     * @param string $order
     * @param string $page
     * @param string $search
     * @since 0.0.2 Refactored completely, added arguments
     * @since 0.0.1
     */
    public function actionGet($table = null, $order = null, $page = null, $search = null)
    {
        $request = Apollo::getInstance()->getRequest();
        $table = strtolower($table);
        if (empty($table)) {
            $request->error(400, 'No table name specified.');
            return;
        }
        if (!in_array($table, ['records'])) {
            $request->error(400, 'Invalid table requested.');
            return;
        }
        echo '<pre>';
        $data = [];
        $data['error'] = null;
        $people = DB::getEntityManager()->createQuery('SELECT u.id, u.given_name, u.last_name FROM Apollo\\Entities\\PersonEntity u');
        $people = $people->getResult();
        $record = DB::getEntityManager()->createQuery('SELECT u.id FROM Apollo\\Entities\\RecordEntity u');
        $record = $record->getResult()[0]['id'];
        $phone = DB::getEntityManager()->createQuery("SELECT u.varchar FROM Apollo\\Entities\\DataEntity u WHERE u.record = :record_id AND u.field = 1");
        $phone->setParameter('record_id', $record);
        $phone = $phone->getResult()[0]['varchar'];
        $email = DB::getEntityManager()->createQuery("SELECT u.varchar FROM Apollo\\Entities\\DataEntity u WHERE u.record = :record_id AND u.field = 2");
        $email->setParameter('record_id', $record);
        $email = $email->getResult()[0]['varchar'];
        $people[0]['phone'] = $phone;
        $people[0]['email'] = $email;
        $data['data'] = $people;
        echo json_encode($data);
    }
}