<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\DB;
use Apollo\Components\Record;
use Apollo\Entities\PersonEntity;
use Apollo\Entities\RecordEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;


/**
 * Class PostController
 *
 * Deals with post request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
 */
class PostController extends GenericController
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
     * @since 0.0.1
     */
    public function notFound()
    {
        Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
    }

    /**
     *
     *
     * @since 0.0.2 Parses
     * @since 0.0.1
     */
    public function actionRecord()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['action' => null]);
        $action = strtolower($data['action']);
        if (!in_array($action, ['create', 'add', 'hide', 'update'])) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
        };
        $response['error'] = null;
        if ($action == 'create') {
            $data = $this->parseRequest(['given_name' => null, 'middle_name' => null, 'last_name' => null, 'record_name' => null, 'start_date' => null, 'end_date' => null]);
            $empty = false;
            foreach ($data as $key => $value) {
                if (empty($value) && $key != 'middle_name') $empty = true;
            }
            if (!$empty) {
                $user = Apollo::getInstance()->getUser();
                $person = new PersonEntity();
                $person->setOrganisation($user->getOrganisation());
                $person->setGivenName($data['given_name']);
                $person->setMiddleName($data['middle_name']);
                $person->setLastName($data['last_name']);
                $em->persist($person);

                $start_date = new DateTime($data['start_date']);
                $end_date = new DateTime($data['end_date']);
                $record = new RecordEntity($user->getEntity());
                $record->setPerson($person);
                $em->persist($record);
                $record->setVarchar(FIELD_RECORD_NAME, $data['record_name']);
                $record->setDateTime(FIELD_START_DATE, $start_date);
                $record->setDateTime(FIELD_END_DATE, $end_date);

                $em->flush();

                $response['record_id'] = $record->getId();
            } else {
                $response['error'] = [
                    'id' => 1,
                    'description' => 'Some of the fields are empty!'
                ];
            }
        }
        if ($action == 'add') {
            $data = $this->parseRequest(['id' => 0, 'name' => null]);
            if (!empty($data['name'])) {
                $response['record_id'] = $data['id'];
                //TODO: Return errors or response IDs
            } else {
                $response['error'] = [
                    'id' => 1,
                    'description' => 'You must specify a name for the new record.'
                ];
            }
        }
        if ($action == 'hide') {
            $data = $this->parseRequest(['id' => 0]);
            if ($data['id'] < 0) {
                Apollo::getInstance()->getRequest()->error(400, 'Invalid ID specified.');
            };
            /**
             * @var EntityRepository $record_repository
             */
            $record_repository = $em->getRepository(Record::getEntityNamespace());
            /**
             * @var RecordEntity $record
             */
            $record = $record_repository->findOneBy(['id' => $data['id'], 'is_hidden' => 0]);
            if ($record != null) {
                $person = $record->getPerson();
                if ($person->getOrganisation()->getId() == Apollo::getInstance()->getUser()->getOrganisationId()) {
                    $records = $person->getRecords();
                    $count = 0;
                    foreach ($records as $current_record) {
                        if (!$current_record->isHidden()) {
                            $count++;
                        }
                    }
                    if ($count > 1) {
                        $record->setIsHidden(true);
                        $em->flush();
                    } else {
                        $response['error'] = [
                            'id' => 1,
                            'description' => 'This is the only visible record for this person, hence cannot be hidden.'
                        ];
                    }
                } else {
                    $response['error'] = [
                        'id' => 1,
                        'description' => 'Record belongs to another organisation!'
                    ];
                }
            } else {
                $response['error'] = [
                    'id' => 1,
                    'description' => 'Selected record is either already hidden or does not exist.'
                ];
            }
        }
        echo json_encode($response);
    }

    /**
     * Parses the request searching for specified keys. If a key is not defined in the POST request,
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
            if (isset($_POST[$key])) {
                $parsedData[$key] = is_int($default) ? intval($_POST[$key]) : $_POST[$key];
            } else {
                $parsedData[$key] = $default;
            }
        }
        return $parsedData;
    }
}