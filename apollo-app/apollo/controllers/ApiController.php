<?php
/**
 * @author Desislava Koleva <desy.koleva96@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
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
 * @version 0.0.3
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
     *TODO: Refactor further, make join requests instead of single requests
     * @param string $table
     * @since 0.0.3 Extracted queryPeopleWithOrg
     * @since 0.0.2 Refactored completely, added arguments
     * @since 0.0.1
     */
    public function actionGet($table = null)
    {
        $request = Apollo::getInstance()->getRequest();
        $table = strtolower($table);
        if (!$this->isTableValid($request, $table)) {
            return;
        }
        $data = [];
        $data['error'] = null;

        $org_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $sort = isset($_GET['sort']) ? intval($_GET['sort']) : 1;
        $sorting = "u.w";
        switch ($sort) {
            case 2:
                break;
            case 3:
                break;
            case 4:
                break;
        }
        $all_people = $this->getPeopleWithOrg($org_id);
        $data = $this->getEssentialInfoFromPeople($all_people, $data);
        echo json_encode($data);
    }

    private function isTableValid($request, $table)
    {
        if (empty($table)) {
            $request->error(400, 'No table name specified.');
            return false;
        } else if (!in_array($table, ['records'])) {
            $request->error(400, 'Invalid table requested.');
            return false;
        } else
            return true;
    }

    /**
     * Gets all people with a certain organisation id.
     * @param $org_id
     * @return array
     * @since 0.0.3
     */
    private function getPeopleWithOrg($org_id)
    {
        $people_query = DB::getEntityManager()
            ->createQuery('SELECT u.id, u.given_name, u.last_name FROM Apollo\\Entities\\PersonEntity u WHERE u.organisation = :organisation AND u.is_hidden = 0');
        $people_query->setParameter('organisation', $org_id);
        $all_people = $people_query->getResult();
        return $all_people;
    }

    /**
     * @param $all_people
     * @param $data
     * @return mixed
     * @since 0.0.3
     */
    private function getEssentialInfoFromPeople($all_people, $data)
    {
        $data['count'] = count($all_people);
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if ($page < 1) $page = 1;
        $upper_bound = $page * 10;
        $lower_bound = 10 * ($page - 1);
        for ($i = $lower_bound; $i < $upper_bound && $i < $data['count']; $i++) {
            $record = $all_people[$i];
            $recordID = $this->getRecordsFromPersonId($record);
            $phone = $this->getPhoneFromRecord($recordID);
            $email = $this->getMailFromRecord($recordID);
            $record['email'] = $email;
            $record['phone'] = $phone;
            $data['data'][] = $record;
        }
        return $data;
    }

    /**
     * @param $person
     * @return \Doctrine\ORM\Query
     * @since 0.0.3
     */
    private function getRecordsFromPersonId($person)
    {
        $recordID = DB::getEntityManager()
            ->createQuery('SELECT u.id FROM Apollo\\Entities\\RecordEntity u WHERE u.person = :person_id AND u.is_hidden = 0 ORDER BY u.created_on DESC');
        $recordID->setParameter('person_id', $person['id']);
        $recordID = $recordID->getResult()[0]['id'];
        return $recordID;
    }

    /**
     * @param $recordID
     * @return \Doctrine\ORM\Query
     * @since 0.0.3
     */
    private function getPhoneFromRecord($recordID)
    {
        $phone = DB::getEntityManager()->createQuery("SELECT u._varchar FROM Apollo\\Entities\\DataEntity u WHERE u.record = :record_id AND u.field = 1");
        $phone->setParameter('record_id', $recordID);
        $phone = $phone->getResult()[0]['_varchar'];
        return $phone;
    }

    /**
     * @param $recordID
     * @return \Doctrine\ORM\Query
     * @since 0.0.3
     */
    private function getMailFromRecord($recordID)
    {
        $email = DB::getEntityManager()->createQuery("SELECT u._varchar FROM Apollo\\Entities\\DataEntity u WHERE u.record = :record_id AND u.field = 2");
        $email->setParameter('record_id', $recordID);
        $email = $email->getResult()[0]['_varchar'];
        return $email;
    }


}