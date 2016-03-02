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
     * @since 0.0.2 Refactored completely, added arguments
     * @since 0.0.1
     */
    public function actionGet($table = null)
    {
        $request = Apollo::getInstance()->getRequest();
        $table = strtolower($table);
        if (empty($table)) {
            $request->error(400, 'No table name specified.');
            return;
        } else if (!in_array($table, ['records'])) {
            $request->error(400, 'Invalid table requested.');
            return;
        }
        $data = [];
        $data['error'] = null;


        $org_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $sort = isset($_GET['sort']) ? intval($_GET['sort']) : 1;
        $sorting = "u.w";
        switch($sort) {
            case 2:
                break;
            case 3:
                break;
            case 4:
                break;
        }
        $people_query = $people = DB::getEntityManager()
            ->createQuery('SELECT u.id, u.given_name, u.last_name FROM Apollo\\Entities\\PersonEntity u WHERE u.organisation = :organisation AND u.is_hidden = 0');
        $people_query->setParameter('organisation', $org_id);
        $all_people = $people_query->getResult();
        $data['count'] = count($all_people);
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if($page < 1) $page = 1;
        $upper_bound = $page * 10;
        for($i = 10 * ($page - 1); $i < ($upper_bound > $data['count'] ? $data['count'] : $upper_bound); $i++) {
            $record = $all_people[$i];
            $recordID = DB::getEntityManager()
                ->createQuery('SELECT u.id FROM Apollo\\Entities\\RecordEntity u WHERE u.person = :person_id AND u.is_hidden = 0 ORDER BY u.created_on DESC');
            $recordID->setParameter('person_id', $record['id']);
            $recordID = $recordID->getResult()[0]['id'];
            $phone = DB::getEntityManager()->createQuery("SELECT u._varchar FROM Apollo\\Entities\\DataEntity u WHERE u.record = :record_id AND u.field = 1");
            $phone->setParameter('record_id', $recordID);
            $phone = $phone->getResult()[0]['_varchar'];
            $email = DB::getEntityManager()->createQuery("SELECT u._varchar FROM Apollo\\Entities\\DataEntity u WHERE u.record = :record_id AND u.field = 2");
            $email->setParameter('record_id', $recordID);
            $email = $email->getResult()[0]['_varchar'];
            $record['email'] = $email;
            $record['phone'] = $phone;
            $data['data'][] = $record;
        }
        echo json_encode($data);
    }
}