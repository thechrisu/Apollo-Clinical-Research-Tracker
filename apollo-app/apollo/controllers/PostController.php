<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\DB;
use Apollo\Components\Field;
use Apollo\Components\Person;
use Apollo\Components\Record;
use Apollo\Entities\ActivityEntity;
use Apollo\Entities\DataEntity;
use Apollo\Entities\FieldEntity;
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
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.5
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
     * Action parsing operations on records, such as hiding, adding, duplicating
     * TODO: Extract!!!!!
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
            $data = $this->parseRequest(['person_id' => 0, 'id' => 0, 'record_name' => null, 'start_date' => null, 'end_date' => null]);
            if (!empty($data['record_name'])) {
                $record = new RecordEntity(Apollo::getInstance()->getUser()->getEntity());
                /** @var PersonEntity $person */
                $person = Person::find($data['person_id']);
                if ($person != null) {
                        $em = DB::getEntityManager();
                        $record->setPerson($person);
                        $em->persist($record);
                        $em->flush();
                        $start_date = new DateTime($data['start_date']);
                        $end_date = new DateTime($data['end_date']);
                        $record->setVarchar(FIELD_RECORD_NAME, $data['record_name']);
                        $record->setDateTime(FIELD_START_DATE, $start_date);
                        $record->setDateTime(FIELD_END_DATE, $end_date);
                        if ($data['id'] > 0) {
                            if(($sourceRecord = Record::find($data['id'])) != null) {
                                $fieldRepo = Field::getRepository();
                                /**
                                 * @var FieldEntity[] $fields
                                 */
                                $fields = $fieldRepo->findBy(['is_hidden' => false, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
                                /**
                                 * @var FieldEntity $field
                                 */
                                foreach ($fields as $field) {
                                    if (!in_array($field->getId(), [FIELD_RECORD_NAME, FIELD_START_DATE, FIELD_END_DATE])) {
                                        $sourceData = $sourceRecord->findOrCreateData($field->getId());
                                        /** @var DataEntity $data */
                                        $data = clone $sourceData;
                                        $data->setRecord($record);
                                        $em->persist($data);
                                    }
                                }
                            } else {
                                $response['error'] = [
                                    'id' => 1,
                                    'description' => 'Source record ID is invalid.'
                                ];
                            }
                        }
                        $em->flush();
                        $response['record_id'] = $record->getId();
                } else {
                    $response['error'] = [
                        'id' => 1,
                        'description' => 'Invalid person ID.'
                    ];
                }
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
                $value = $_POST[$key];
                if (is_int($default)) $value = intval($value);
                $parsedData[$key] = $value;
            } else {
                $parsedData[$key] = $default;
            }
        }
        return $parsedData;
    }

    /**
     * Parses the data/field info and saves it into database
     *
     * TODO Tim: Fix dates, Extract!!!!!!!!!
     *
     * @since 0.0.4
     */
    public function actionData()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['record_id' => 0, 'field_id' => 0, 'value' => null, 'is_default' => null]);
        $response['error'] = null;
        $organisation = Apollo::getInstance()->getUser()->getOrganisation();
        /** @var RecordEntity $record */
        if ($data['record_id'] > 0 && ($record = Record::getRepository()->find($data['record_id'])) != null) {
            if (in_array($data['field_id'], [FIELD_GIVEN_NAME, FIELD_MIDDLE_NAME, FIELD_LAST_NAME])) {
                if ($data['value'] !== null) {
                    if ($data['field_id'] == FIELD_GIVEN_NAME) {
                        $record->getPerson()->setGivenName($data['value']);
                    } elseif ($data['field_id'] == FIELD_MIDDLE_NAME) {
                        $record->getPerson()->setMiddleName($data['value']);
                    } elseif ($data['field_id'] == FIELD_LAST_NAME) {
                        $record->getPerson()->setLastName($data['value']);
                    }
                    $em->flush();
                } else {
                    $response['error'] = [
                        'id' => 1,
                        'description' => 'Value cannot be equal to null.'
                    ];
                }
                /** @var FieldEntity $field */
            } elseif ($data['field_id'] > 0 && ($field = Field::getRepository()->findOneBy(['id' => $data['field_id'], 'organisation' => $organisation])) != null) {
                if ($data['value'] !== null) {
                    $dataObject = $record->findOrCreateData($data['field_id']);
                    switch ($field->getType()) {
                        case 1:
                            $dataObject->setInt(intval($data['value']));
                            break;
                        case 2:
                            if ($field->hasDefault()) {
                                if ($data['is_default'] != null) {
                                    $dataObject->setIsDefault(true);
                                    if ($field->isMultiple()) {
                                        for ($i = 0; $i < count($data['value']); $i++) {
                                            $data['value'][$i] = intval($data['value'][$i]);
                                        }
                                        $dataObject->setLongText(serialize($data['value']));
                                    } else {
                                        $dataObject->setInt(intval($data['value']));
                                    }
                                } else {
                                    $dataObject->setIsDefault(false);
                                    $dataObject->setVarchar($data['value']);
                                }
                            } elseif ($field->isMultiple()) {
                                $dataObject->setLongText(serialize($data['value']));
                            } else {
                                $dataObject->setVarchar($data['value']);
                            }
                            break;
                        case 3:
                            $date = new DateTime($data['value']);
                            $dataObject->setDateTime($date);
                            break;
                        case 4:
                            $dataObject->setLongText($data['value']);
                            break;
                    }
                    $em->flush();
                } else {
                    $response['error'] = [
                        'id' => 1,
                        'description' => 'Value cannot be equal to null.'
                    ];
                }
            } else {
                $response['error'] = [
                    'id' => 1,
                    'description' => 'Supplied field ID is invalid.'
                ];
            }
        } else {
            $response['error'] = [
                'id' => 1,
                'description' => 'Supplied record ID is invalid.'
            ];
        }
        echo json_encode($response);
    }

    public function actionActivity()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['action' => null]);
        $action = strtolower($data['action']);
        if (!in_array($action, ['create', 'hide', 'update'])) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
        };
        $response['error'] = null;
        if ($action == 'create') {
            $data = $this->parseRequest(['activity_name' => null, 'start_date' => null, 'end_date' => null]);
            $empty = false;
            foreach ($data as $key => $value) {
                if (empty($value)) $empty = true;
            }
            if (!$empty) {
                $user = Apollo::getInstance()->getUser();
                $start_date = new DateTime($data['start_date']);
                $end_date = new DateTime($data['end_date']);
                $activity = new ActivityEntity();
                $activity->setOrganisation($user->getOrganisation());
                $activity->setName($data['activity_name']);
                $activity->setStartDate($start_date);
                $activity->setEndDate($end_date);
                $em->persist($activity);
                try {
                    $em->flush();
                    $response['activity_id'] = $activity->getId();
                } catch (Exception $e) {
                    $response['errror'] = [
                        'id' => 2,
                        'description' => $e->getMessage()
                    ];
                }
            } else {
                $response['error'] = [
                    'id' => 1,
                    'description' => 'Some of the fields are empty!'
                ];
            }
        }
        $dummy = [
            'error' => null,
            'activity_id' => 1
        ];
        echo json_encode($response);
    }
}