<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\Record;
use Apollo\Entities\PersonEntity;
use Apollo\Components\DB;
use Apollo\Components\Person;
use Apollo\Entities\RecordEntity;
use Apollo\Components\Activity;
use Apollo\Entities\ActivityEntity;


/**
 * Class GetController
 *
 * Deals with get request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.8
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
     * Return 400 error on invalid action
     *
     * @since 0.0.4
     */
    public function notFound()
    {
        Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
    }

    /**
     * Posts a JSON that contains information of the records (for the overview-view)
     * TODO: Contemplate over putting extracted functions somewhere else
     * @since 0.0.8 Extracted a lot more functions
     * @since 0.0.5 Extracted sorting
     * @since 0.0.2 Implemented quick search
     * @since 0.0.1
     */
    public function actionRecords()
    {
        $data = $this->parseRequest(['page' => 1, 'sort' => 1, 'search' => null]);
        $page = $data['page'] > 0 ? $data['page'] : 1;

        $peopleQB = $this->createQueryForRecordsRequest($data);
        $peopleQuery = $peopleQB->getQuery();
        $people =  $peopleQuery->getResult();
        $response = $this->getFormattedRecordsOfPeople($people, $page);
        echo json_encode($response);
    }

    /**
     * @param $request
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    private function createQueryForRecordsRequest($request)
    {
        $em = DB::getEntityManager();
        $peopleRepo = $em->getRepository(Person::getEntityNamespace());
        $peopleQB = $peopleRepo->createQueryBuilder('person');
        $peopleQB->innerJoin('person.records', 'record');
        $peopleQB->where('person.organisation = ' . Apollo::getInstance()->getUser()->getOrganisationId());
        $peopleQB->andWhere('person.is_hidden = 0');
        $peopleQB = $this->orderRecords($peopleQB, $request['sort']);
        if (!empty($request['search'])) {
            $this->addPersonSearch($peopleQB, $request['search']);
            return $peopleQB;
        }
        return $peopleQB;
    }

    /**
     * Given a query bulider for records and an ordering, set up the ordering depending on how it is requested
     * @param $queryBuilder
     * @param $ordering
     * @return mixed
     */
    private function orderRecords($queryBuilder, $ordering)
    {
        switch ($ordering) {
            // Recently added
            case 2:
                $queryBuilder->orderBy('record.created_on', 'DESC');
                break;
            // Recently updated
            case 3:
                $queryBuilder->orderBy('record.updated_on', 'DESC');
                break;
            // All records
            default:
                $queryBuilder->orderBy('person.given_name', 'ASC');
                $queryBuilder->addOrderBy('person.middle_name', 'ASC');
                $queryBuilder->addOrderBy('person.last_name', 'ASC');
        }
        return $queryBuilder;
    }


    /**
     * TODO: Add description for function
     *
     * @param $queryBuilder
     * @param $search
     */
    private function addPersonSearch($queryBuilder, $search)
    {
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('person.given_name', ':search'),
            $queryBuilder->expr()->like('person.middle_name', ':search'),
            $queryBuilder->expr()->like('person.last_name', ':search')
        ));
        $queryBuilder->setParameter('search', '%' . $search . '%');
    }

    /**
     * Given a person (retrieved from the data base), returns an object containing all the data of the person
     * TODO: Put this function somewhere else, this is a controller
     * @param $person
     * @return mixed
     */
    private function getFormattedPersonData($person)
    {
        $responsePerson = [];
        $recentRecord = Person::getMostRecentRecord($person->getId());
        $responsePerson['id'] = $recentRecord->getId();
        $responsePerson['given_name'] = $person->getGivenName();
        $responsePerson['last_name'] = $person->getLastName();
        $responsePerson['phone'] = $recentRecord->findVarchar(FIELD_PHONE);
        $responsePerson['email'] = $recentRecord->findVarchar(FIELD_EMAIL);
        return $responsePerson;
    }

    /**
     * For a given number of people, return a formatted object of the people's record data
     * TODO: Put this somewhere else, this is a controller
     * @param $people
     * @param $page
     * @return $response
     */
    private function getFormattedRecordsOfPeople($people, $page)
    {
        $response['error'] = null;
        $response['count'] = count($people);
        /**
         * @var PersonEntity $person
         */
        for ($i = 10 * ($page - 1); $i < min($response['count'], $page * 10); $i++) {
            $person = $people[$i];
            $personRecords = $person->getRecords();
            if (count($personRecords) < 1) {
                $response['error'] = ['id' => 1, 'description' => 'Person #' . $person->getId() . ' has 0 records!'];
            } else {
                $response['data'][] = $this->getFormattedPersonData($person);
            }
        }
        return $response;
    }

    /**
     * Returns dummy record data
     *
     * @since 0.0.3
     */
    public function actionRecord() {
        //TODO: refactor this to use actual data

        /**
         * @var RecordEntity $record
         */
        $record = Record::getRepository()->find(intval($_GET['id']));
        Record::prepare($record);

        $data['error'] = null;

        /**
         * @var RecordEntity[] $other_records
         */
        $other_records = Record::getRepository()->findBy(['person' => $record->getPerson()->getId(), 'is_hidden' => 0]);
        $id_array = [];
        $name_array = [];
        foreach($other_records as $other_record) {
            if($other_record->getId() != intval($_GET['id'])) {
                $id_array[] = $other_record->getId();
                $name_array[] = $other_record->findVarchar(FIELD_RECORD_NAME);
            }
        }

        $data['essential'] = [
            "given_name" => $record->getPerson()->getGivenName(),
            "middle_name" => $record->getPerson()->getMiddleName(),
            "last_name" => $record->getPerson()->getLastName(),
            "email" => $record->findOrCreateData(FIELD_EMAIL)->getVarchar(),
            //TODO Tim: make a proper arrayifying of the address
            "address" => [$record->findOrCreateData(FIELD_ADDRESS)->getVarchar(), "London, SE1 7TP"],
            "phone" => $record->findOrCreateData(FIELD_PHONE)->getVarchar(),
            "start_date" => $record->findOrCreateData(FIELD_START_DATE)->getDateTime()->format('Y-m-d H:i:s'),
            "end_date" => $record->findOrCreateData(FIELD_END_DATE)->getDateTime()->format('Y-m-d H:i:s'),
            "person_id" => $record->getPerson()->getId(),
            "record_id" => $record->getId(),
            "record_name" => $record->findOrCreateData(FIELD_RECORD_NAME)->getVarchar(),
            "record_ids" => $id_array,
            "record_names" => $name_array
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
                "name" => "Work experience",
                "type" => 2,
                "value" => ['D-Wave Systems, Junior Research Scientist and Software Engineer', 'EXI Wireless, Bluetooth Group Software and Hardware Engineer', 'Nortel Networks, OPTera Solutions, Photonic Group']
            ],
            [
                "name" => "Intentionally left blank",
                "type" => 2,
                "value" => null
            ],
            [
                "name" => "Subjects",
                "type" => 2,
                "value" => ['Software Engineering', 'Compiling Techniques', 'Cryptography']
            ],
            [
                "name" => "References",
                "type" => 4,
                "value" => "Mister Bond is one of our nicest employees. He even developed new applications in conjunction with Q."
            ],
            [
                "name" => "Birthday",
                "type" => 3,
                "value" => "1974-02-22 02:00:00"
            ],
            [
                "name" => "Some other date",
                "type" => 3,
                "value" => "2067-11-12 02:00:00"
            ],
            [
            "name" => "Lab skills",
            "type" => 2,
            "value" => ['Digital/Analog Scopes', 'Spectrum Analyzer', 'Function Generators']
        ]
        ];
        echo json_encode($data);
    }

    /**
     * It returns short information about several activities
     * Currently serves dummy data
     * @since 0.0.5
     * TODO: Serve real data
     */
    public function actionActivities()
    {
        $activityRepo = $em->getRepository(Person::getEntityNamespace());
        $peopleQB = $peopleRepo->createQueryBuilder('person');
        $data['error'] = null;
        $data['count'] = 12;
        $data['activities'] = [
            [
                "name" => "Programme 1",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "1"
            ],
            [
                "name" => "Programme 2",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "2"
            ],
            [
                "name" => "Programme 3",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "3"
            ],
            [
                "name" => "Programme 1",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "1"
            ],
            [
                "name" => "Programme 2",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "2"
            ],
            [
                "name" => "Programme 3",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "3"
            ],
            [
                "name" => "Programme 1",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "1"
            ],
            [
                "name" => "Programme 2",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "2"
            ],
            [
                "name" => "Programme 3",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "3"
            ],
            [
                "name" => "Programme 1",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "1"
            ],
            [
                "name" => "Programme 2",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "2"
            ],
            [
                "name" => "Programme 3",
                "start_date" => "1834-02-22 02:00:00",
                "end_date" => "1834-02-22 02:00:00",
                "id" => "3"
            ]
        ];
        echo json_encode($data);
    }

    /**
     * Returns detailed information on one activity
     * @since 0.0.6
     */
    public function actionActivity()
    {
        $data['error'] = null;
        $data['name'] = "Some activity";
        $data['target_group'] = ["Young people", "Old people", "Twentysomething people"];
        $data['current_target_group'] = 0;
        $data['target_group_comment'] = "This is an exceptional activity";
        $data['start_date'] = "1834-01-22 02:00:00";
        $data['end_date'] = "1834-02-22 02:00:00";
        $data['participants'] = [

            [
                "given_name" => "Peter",
                "last_name" => "Parker",
                "id" => "13"
            ],
            [
                "given_name" => "Michael",
                "last_name" => "Jackdaughter",
                "id" => "12"
            ],
            [
                "given_name" => "Rowan",
                "last_name" => "@kinson",
                "id" => "1"
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