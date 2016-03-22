<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
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


/**
 * Class GetController
 *
 * Deals with get request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.4
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
                $recentRecord = null;
                $recentDate =  null;
                foreach($person->getRecords() as $currentRecord) {
                    if(!$currentRecord->isHidden()) {
                        $currentDate = $currentRecord->findDateTime(FIELD_START_DATE);
                        if ($recentDate == null || $recentDate < $currentDate) {
                            $recentDate = $currentDate;
                            $recentRecord = $currentRecord;
                        }
                    }
                }
                $responsePerson['id'] = $recentRecord->getId();
                $responsePerson['given_name'] = $person->getGivenName();
                $responsePerson['last_name'] = $person->getLastName();
                $responsePerson['phone'] = $recentRecord->findVarchar(FIELD_PHONE);
                $responsePerson['email'] = $recentRecord->findVarchar(FIELD_EMAIL);
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