<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Entities\PersonEntity;
use Apollo\Components\DB;
use Apollo\Components\Person;


/**
 * Class GetController
 *
 * Deals with get request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.3
 */
class GetController extends GenericController
{
    /**
     * Since an empty request to API is not valid, return 400 error
     *
     * @since 0.0.1
     */
    public function index()
    {
        Apollo::getInstance()->getRequest()->error(400, 'No action is specified.');
    }

    /**
     * Returns the records
     *
     * @since 0.0.2 Implemented quick search
     * @since 0.0.1
     */
    public function actionRecords()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['page' => 1, 'sort' => 1, 'search' => null]);
        $page = $data['page'] > 0 ? $data['page'] : 1;

        $peopleRepo = $em->getRepository(Person::getEntityNamespace());
        $peopleQB = $peopleRepo->createQueryBuilder('person');
        $peopleQB->innerJoin('person.records', 'record');
        $peopleQB->where('person.organisation = ' . Apollo::getInstance()->getUser()->getOrganisationId());
        $peopleQB->andWhere('person.is_hidden = 0');
        switch ($data['sort']) {
            // Recently added
            case 2:
                $peopleQB->orderBy('record.created_on', 'DESC');
                break;
            // Recently updated
            case 3:
                $peopleQB->orderBy('record.updated_on', 'DESC');
                break;
            // All records
            default:
                $peopleQB->orderBy('person.given_name', 'ASC');
                $peopleQB->addOrderBy('person.middle_name', 'ASC');
                $peopleQB->addOrderBy('person.last_name', 'ASC');
        }
        if(!empty($data['search'])) {
            $peopleQB->andWhere($peopleQB->expr()->orX(
                $peopleQB->expr()->like('person.given_name', ':search'),
                $peopleQB->expr()->like('person.middle_name', ':search'),
                $peopleQB->expr()->like('person.last_name', ':search')
            ));
            $peopleQB->setParameter('search', '%' . $data['search'] . '%');
        } else {
        }
        $peopleQuery = $peopleQB->getQuery();
        $people =  $peopleQuery->getResult();
        $response['error'] = null;
        $response['count'] = count($people);
        /**
         * @var PersonEntity $person
         */
        for($i = 10 * ($page - 1); $i < min($response['count'], $page * 10); $i++) {
            $person = $people[$i];
            $responsePerson = [];
            $personRecords = $person->getRecords();
            if(count($personRecords) < 1) {
                $response['error'] = ['id' => 1, 'description' => 'Person #' . $person->getId() . ' has 0 records!'];
            } else {
                $recentRecord = $person->getRecords()[0];
                $responsePerson['id'] = $recentRecord->getId();
                $responsePerson['given_name'] = $person->getGivenName();
                $responsePerson['last_name'] = $person->getLastName();
                $responsePerson['phone'] = $recentRecord->getData()[0]->getVarchar();
                $responsePerson['email'] = $recentRecord->getData()[1]->getVarchar();
                $response['data'][] = $responsePerson;
            }
        }
        echo json_encode($response);
    }

    /**
     * Returns dummy record data
     *
     * @since 0.0.3
     */
    public function actionRecord() {
        $data['error'] = null;
        $data['essential'] = [
            "given_name" => "James",
            "middle_name" => "Houka",
            "last_name" => "Bond",
            "email" => "james.bond@mi6.gov.uk",
            "address" => ["85 Albert Embankment", "London, SE1 7TP"],
            "phone" => "+44 0000 007",
            "record_id" => 1,
            "record_name" => "Main",
            "record_ids" => [2, 3, 4, 5],
            "record_names" => ['Second', 'Third', 'Fourth', 'Fifth']
        ];
        $data['data'] = [
            [
                "name" => "Supervisor",
                "type" => 2,
                "value" => "M&Ms"
            ],
            [
                "name" => "Car",
                "type" => 2,
                "value" => "Aston Martin DB5"
            ],
            [
                "name" => "Japanese names",
                "type" => 2,
                "value" => ['Ryuko', 'Satsuki', 'Misaka', 'Asuna', 'Minene', 'Kurisu']
            ],
            [
                "name" => "References",
                "type" => 4,
                "value" => "Mister Bond is one of our nicest employees. In fact, he even developed new applications in conjunction with Q. He is always punctual."
            ],
            [
                "name" => "Birthday",
                "type" => 3,
                "value" => "1834-02-22 02:00:00"
            ]
        ];
        echo json_encode($data);
    }

    /**
     * Parses the request searching for specified keys. If a key is not defined in the GET request,
     * use the default value specified in the array.
     *
     * @param array $data
     * @return array
     * @since 0.0.1
     */
    public function parseRequest($data)
    {
        $parsedData = [];
        foreach ($data as $key => $default) {
            if (isset($_GET[$key])) {
                $parsedData[$key] = is_int($default) ? intval($_GET[$key]) : $_GET[$key];
            } else {
                $parsedData[$key] = $default;
            }
        }
        return $parsedData;
    }
}