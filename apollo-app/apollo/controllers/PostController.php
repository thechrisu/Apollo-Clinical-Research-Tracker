<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\DB;
use Apollo\Entities\DefaultEntity;
use Apollo\Components\Activity;
use Apollo\Components\Field;
use Apollo\Components\Person;
use Apollo\Components\Record;
use Apollo\Components\TargetGroup;
use Apollo\Entities\ActivityEntity;
use Apollo\Entities\DataEntity;
use Apollo\Entities\FieldEntity;
use Apollo\Entities\PersonEntity;
use Apollo\Entities\RecordEntity;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use Exception;


/**
 * Class PostController
 *
 * Deals with post request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @todo: Extract queries to other components
 * @todo: Extract functions for all of the fields
 * @version 0.1.4
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
     * @todo: Extract till you drop!!!!!
     * @todo: ^ Well that didn't work
     * @since 0.0.2 Parses
     * @since 0.0.1
     */
    public function actionRecord()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['action' => null]);
        $action = strtolower($data['action']);
        $response['error'] = null;
        switch($action) {
            case 'create':
                $data = $this->parseRequest(['given_name' => null, 'middle_name' => null, 'last_name' => null, 'record_name' => null, 'start_date' => null, 'end_date' => null]);
                if (!empty($data['given_name']) && !empty($data['last_name']) && !empty($data['record_name']) && !empty($data['start_date']) && !empty($data['end_date'])) {
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
                    $response['error'] = $this->getJSONError(1, 'Some of the fields are empty!');
                }
                break;
            case 'add':
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
                            if (($sourceRecord = Record::getValidRecordWithId($data['id'])) != null) {
                                $fields = Field::getValidFields();
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
                                $response['error'] = $this->getJSONError(1, 'Source record ID is invalid.');
                            }
                        }
                        $em->flush();
                        $response['record_id'] = $record->getId();
                    } else {
                        $response['error'] = $this->getJSONError(1, 'Invalid person ID.');
                    }
                } else {
                    $response['error'] = $this->getJSONError(1, 'You must specify a name for the new record.');
                }
                break;
            case 'hide':
                $data = $this->parseRequest(['id' => 0]);
                if ($data['id'] < 0) {
                    Apollo::getInstance()->getRequest()->error(400, 'Invalid ID specified.');
                };
                /**
                 * @var RecordEntity $record
                 */
                $record = Record::getValidRecordWithId($data['id']);
                if ($record != null) {
                    $person = $record->getPerson();
                    if (!empty($person)) {
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
                        $response['error'] = $this->getJSONError(1, 'Record belongs to another organisation!');
                    }
                } else {
                    $response['error'] = $this->getJSONError(1, 'Selected record is either already hidden or does not exist.');
                }
                break;
            default:
                Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
        }
        echo json_encode($response);
    }

    /**
     * @since 1.4
     */
    public function actionPerson()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['action' => null]);
        $action = strtolower($data['action']);
        $response['error'] = null;
        switch($action) {
            case 'hide':
                $data = $this->parseRequest(['id' => 0]);
                if ($data['id'] < 0) {
                    Apollo::getInstance()->getRequest()->error(400, 'Invalid ID specified.');
                };
                /**
                 * @var PersonEntity $record
                 */
                $person = Person::getValidPersonWithId($data['id']);
                if ($person != null) {
                    if (!empty($person)) {
                        $records = $person->getRecords();
                        foreach ($records as $current_record) {
                            $current_record->setIsHidden(true);
                            $em->persist($current_record);
                        }
                        $person->setIsHidden(true);
                        $em->persist($person);
                        $em->flush();
                    } else {
                        $response['error'] = $this->getJSONError(1, 'Person belongs to another organisation!');
                    }
                } else {
                    $response['error'] = $this->getJSONError(1, 'Selected person is either already hidden or does not exist.');
                }
                break;
            default:
                Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
        }
        echo json_encode($response);
    }


    /**
     * Parses the data/field info and saves it into database
     * @todo: extract
     * @todo: Update records updated_by
     *
     * @since 0.0.4
     */
    public function actionData()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['record_id' => 0, 'field_id' => 0, 'value' => null, 'is_default' => null]);
        $response['error'] = null;
        /** @var RecordEntity $record */
        if ($data['record_id'] > 0 && ($record = Record::getValidRecordWithId($data['record_id'])) != null) {
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
            } elseif ($data['field_id'] > 0 && ($field = Field::getValidFieldWithId($data['field_id'])) != null) {
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
                    $response['error'] = $this->getJSONError(1, 'Value cannot be equal to null.');
                }
            } else {
                $response['error'] = $this->getJSONError(1, 'Supplied field ID is invalid.');
            }
        } else {
            $response['error'] = $this->getJSONError(1, 'Supplied record ID is invalid.');
        }
        echo json_encode($response);
    }

    /**
     * Action responsible for handling requests related to fields
     * @todo: extract.
     * @param string $action
     */
    public function actionField($action = null)
    {
        $response['error'] = null;
        if($action == 'update') {
            $data = $this->parseRequest(['type' => null, 'id' => 0, 'value' => null]);
            if(!empty($data['type']) && $data['id'] > 0 && !empty($data['value'])) {
                if(in_array($data['type'], ['name', 'defaults'])) {
                    /** @var FieldEntity $field */
                    $field = Field::getValidFieldWithId($data['id']);
                    if($field != null) {
                        $em = DB::getEntityManager();
                        if($data['type'] == 'name') {
                            $field->setName($data['value']);
                        } elseif($data['type'] == 'defaults') {
                            //@todo: Anything but this
                            $length = max(count($data['value']), count($field->getDefaults()));
                            $defaults = $field->getDefaults();
                            for($i = 0; $i < $length; $i++) {
                                if(count($field->getDefaults()) > $i) {
                                    $default = $defaults[$i];
                                    if(count($data['value']) > $i) {
                                        $default->setValue($data['value'][$i]);
                                        $default->setOrder($i);
                                    } else {
                                        $em->remove($default);
                                    }
                                } else {
                                    $default = new DefaultEntity();
                                    $default->setField($field);
                                    $default->setValue($data['value'][$i]);
                                    $default->setOrder($i);
                                    $em->persist($default);
                                }
                            }
                            $em->flush();
                        }
                        $em->flush();
                    } else {
                        $error['id'] = 0;
                        $error['description'] = 'Invalid ID.';
                    }
                } else {
                    $error['id'] = 0;
                    $error['description'] = 'Invalid field type.';
                }
            } else {
                $error['id'] = 0;
                $error['description'] = 'Missing post request parameters.';
            }
        } elseif($action == 'add') {
            $data = $this->parseRequest(['name' => null, 'type' => null]);
            if(!empty($data['name']) && !empty($data['type'])) {
                $field = new FieldEntity();
                $field->setOrganisation(Apollo::getInstance()->getUser()->getOrganisation());
                $field->setName($data['name']);
                switch($data['type']) {
                    case 'integer':
                        $field->setType(1);
                        break;
                    case 'single':
                        $field->setType(2);
                        break;
                    case 'multiple':
                        $field->setType(2);
                        $field->setIsMultiple(true);
                        break;
                    case 'dropdown':
                        $field->setType(2);
                        $field->setHasDefault(true);
                        break;
                    case 'dropdown-other':
                        $field->setType(2);
                        $field->setHasDefault(true);
                        $field->setAllowOther(true);
                        break;
                    case 'dropdown-multiple':
                        $field->setType(2);
                        $field->setHasDefault(true);
                        $field->setIsMultiple(true);
                        break;
                    case 'date':
                        $field->setType(3);
                        break;
                    case 'text':
                        $field->setType(4);
                        break;
                }
                DB::getEntityManager()->persist($field);
                DB::getEntityManager()->flush();
                if(in_array($data['type'], ['dropdown', 'dropdown-other', 'dropdown-multiple'])) {
                    $default = new DefaultEntity();
                    $default->setField($field);
                    $default->setOrder(0);
                    $default->setValue('Default value');
                    DB::getEntityManager()->persist($default);
                    DB::getEntityManager()->flush();
                }
            } else {
                $error['id'] = 0;
                $error['description'] = 'Missing post request parameters.';
            }
        } elseif($action == 'hide') {
            $data = $this->parseRequest(['id' => 0]);
            $field = Field::getValidFieldWithId($data['id']);
            if($field != null) {
                if(!$field->isEssential()) {
                    $field->setIsHidden(true);
                    DB::getEntityManager()->flush();
                } else {
                    $error['id'] = 0;
                    $error['description'] = 'Field is marked as essential and hence cannot be hidden.';
                }
            } else {
                $error['id'] = 0;
                $error['description'] = 'Invalid ID.';
            }
        } else {
            $error['id'] = 0;
            $error['description'] = 'Invalid action.';
            $response['error'] = $error;
        }
        echo json_encode($response);
    }

    /**
     * Parses filters for advanced search requests
     *
     * @since 0.1.2
     */
    public function actionSearch() {
        $data = $this->parseRequest(['page' => 1, 'sort' => 1, 'states' => null]);
        $page = $data['page'] > 0 ? $data['page'] : 1;

        $peopleQB = $this->createQueryForRecordsRequest($data);
        $peopleQuery = $peopleQB->getQuery();
        $people = $peopleQuery->getResult();
        $people = $this->applyFilters($people, $data['states']);
        $response = Person::getFormattedRecordsOfPeople($people, $page);
        echo json_encode($response);
    }

    /**
     * @param $request
     * @return QueryBuilder|mixed
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
        return $peopleQB;
    }

    /**
     * Given a query bulider for records and an ordering, set up the ordering depending on how it is requested
     * @param QueryBuilder $queryBuilder
     * @param int $ordering
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
     * Parses filters and applies them to the return values
     *
     * @todo: Include non-recent records too
     * @todo: extract. extract. extract. (this function is ~190 lines long!)
     * @param PersonEntity[] $people
     * @param $states
     * @return mixed[]
     */
    private function applyFilters($people, $states = null)
    {
        $filteredPeople = [];
        $fieldRepository = Field::getRepository();
        if($states == null) {
            $filteredPeople = $people;
        } else {
            for($i = 0; $i < count($people); $i++) {
                $add = false;
                $person = $people[$i];
                $record = Person::getMostRecentRecord($person);
                for($k = 0; $k < count($states); $k++) {
                    $state = $states[$k];
                    /** @var FieldEntity $field */
                    $fieldID = intval($state['field']);
                    $field = $fieldRepository->find($fieldID);
                    $relation = intval($state['relation']);
                    switch($field->getType()) {
                        case 2:
                            $value = !empty($state['value']) ? $state['value'] : '';
                            if($field->hasDefault()) {
                                if($field->isAllowOther()) {
                                    $data = $record->findOrCreateData($fieldID);
                                    if($relation != 2 && $data->isDefault()) {
                                        $value = intval($value);
                                        $recordValue = $record->findInt($fieldID);
                                        switch ($relation) {
                                            case 1:
                                                $add = $recordValue != $value;
                                                break;
                                            default:
                                                $add = $recordValue == $value;
                                        }
                                    } elseif($relation == 2 && !$data->isDefault()) {
                                        $recordValue = $data->getVarchar();
                                        $add = empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false;
                                    } else {
                                        $add = false;
                                    }
                                } elseif($field->isMultiple()) {
                                    $value = intval($value);
                                    $recordValues = $record->findMultiple($fieldID);
                                    switch ($relation) {
                                        case 1:
                                            $add = !in_array($value, $recordValues);
                                            break;
                                        default:
                                            $add = in_array($value, $recordValues);
                                    }
                                } else {
                                    $value = intval($value);
                                    $recordValue = $record->findInt($fieldID);
                                    switch ($relation) {
                                        case 1:
                                            $add = $recordValue != $value;
                                            break;
                                        default:
                                            $add = $recordValue == $value;
                                    }
                                }
                            } elseif($field->isMultiple()) {
                                $recordValues = $record->findMultiple($fieldID);
                                switch ($relation) {
                                    case 1:
                                        $contains = false;
                                        foreach($recordValues as $recordValue) {
                                            if(empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false) {
                                                $contains = true;
                                                break;
                                            }
                                        }
                                        $add = !(empty($value) || $contains);
                                        break;
                                    case 2:
                                        $empty = true;
                                        foreach($recordValues as $recordValue) {
                                            if(!empty($recordValue)) {
                                                $empty = false;
                                                break;
                                            }
                                        }
                                        $add = $empty;
                                        break;
                                    case 3:
                                        $empty = true;
                                        foreach($recordValues as $recordValue) {
                                            if(!empty($recordValue)) {
                                                $empty = false;
                                                break;
                                            }
                                        }
                                        $add = !$empty;
                                        break;
                                    default:
                                        $contains = false;
                                        foreach($recordValues as $recordValue) {
                                            if(empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false) {
                                                $contains = true;
                                                break;
                                            }
                                        }
                                        $add = empty($value) || $contains;
                                }
                            } else {
                                $recordValue = $record->findVarchar($fieldID);
                                switch ($relation) {
                                    case 1:
                                        $add = !(empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false);
                                        break;
                                    case 2:
                                        $add = $recordValue == $value;
                                        break;
                                    case 3:
                                        $add = $recordValue != $value;
                                        break;
                                    case 4:
                                        $add = empty($recordValue);
                                        break;
                                    case 5:
                                        $add = !empty($recordValue);
                                        break;
                                    default:
                                        $add = empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false;
                                }
                            }
                            break;
                        case 3:
                            $value = new DateTime($state['value']);
                            $recordValue = $record->findDateTime($fieldID);
                            switch ($relation) {
                                case 1:
                                    $add = $recordValue != $value;
                                    break;
                                case 2:
                                    $add = $recordValue < $value;
                                    break;
                                case 3:
                                    $add = $recordValue > $value;
                                    break;
                                default:
                                    $add = $recordValue == $value;
                            }
                            break;
                        case 4:
                            $value = $state['value'];
                            $recordValue = $record->findLongText($fieldID);
                            switch ($relation) {
                                case 1:
                                    $add = !(empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false);
                                    break;
                                case 2:
                                    $add = empty($recordValue);
                                    break;
                                case 3:
                                    $add = !empty($recordValue);
                                    break;
                                default:
                                    $add = empty($value) || strpos(strtolower($recordValue), strtolower($value)) !== false;
                            }
                            break;
                        default:
                            $value = intval($state['value']);
                            $recordValue = $record->findInt($fieldID);
                            switch ($relation) {
                                case 1:
                                    $add = $recordValue != $value;
                                    break;
                                case 2:
                                    $add = $recordValue < $value;
                                    break;
                                case 3:
                                    $add = $recordValue > $value;
                                    break;
                                default:
                                    $add = $recordValue == $value;
                            }
                    }
                    if(!$add) {
                        break;
                    }
                }
                if($add) {
                    $filteredPeople[] = $person;
                }
            }
        }
        return $filteredPeople;
    }

    /**
     * @since 0.0.6
     */
    public function actionActivity()
    {
        $data = $this->parseRequest(['action' => null]);
        $action = strtolower($data['action']);
        $response = [];
        switch($action){
            case 'create':
                $response = $this->activityCreate();
                break;
            case 'hide':
                $response = $this->activityHide();
                break;
            case 'update':
                $response = $this->activityUpdate();
                break;
            default:
                Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
                break;
        }
        //$this->derail($response);
        echo json_encode($response);
    }

    /**
     * Handles the post request for adding a new activity
     * @return mixed
     * @since 0.0.6
     */
    private function activityCreate()
    {
        $response['error'] = null;
        $data = $this->parseRequest(['id' => -1, 'activity_name' => null, 'start_date' => null, 'end_date' => null]);
        $bonus = null;
        if ($data['id'] > 0) {
            try{
                $sourceActivity = Activity::getValidActivityWithId($data['id']);
                if ($sourceActivity != null) {
                    $bonus['target_group'] = $sourceActivity->getTargetGroup();
                    $bonus['target_group_comment'] = $sourceActivity->getTargetGroupComment();
                    $bonus['people'] = $sourceActivity->getPeople();
                } else {
                    $response['error'] = $this->getJSONError(2, 'Source activity ID is invalid.');
                }
            } catch (Exception $e) {
                $response['error'] = $this->getJSONError(3, 'Error while duplicating. Message: ' . $e->getMessage());
            }
        }
        if (!$this->areFieldsEmpty($data)) {
            $activity = $this->createActivityFromData($data, $bonus);
            $response = $this->registerActivity($activity);
        } else {
            $response['error'] = $this->getJSONError(1, 'Some of the fields are empty');
        }
        return $response;
    }

    /**
     * Handles the request for when an activity should be hidden
     * @return mixed
     */
    private function activityHide()
    {
        $response['error'] = null;
        $data = $this->parseRequest(['activity_id' => null]);
        $activity = null;
        try {
            $activity = Activity::getValidActivityWithId($data['activity_id']);
        } catch (Exception $e) {
            $response['error'] = $this->getJSONError(2, 'Error while querying database for activity, message: ' . $e->getMessage());
        } finally {
            if ($data['activity_id'] > 0 & $activity != null) {
                try {
                    $activity->setIsHidden(true);
                    $this->writeActivityToDB($activity);
                } catch (Exception $e) {
                    $response['error'] = $this->getJSONError(3, 'Error while writing to database for activity, message: ' . $e->getMessage());
                }
            } else {
                $response['error'] = $this->getJSONError(1, 'Error in activity id, could not hide');
            }
            return $response;
        }
    }

    /**
     * @todo: make this better? put this in components? in any case, do something with it. This looks horrible
     * @return mixed
     */
    private function activityUpdate()
    {
        $response['error'] = null;
        $data = $this->parseRequest([
            'activity_id' => null,
            'activity_name' => null,
            'target_group' => null,
            'target_group_comment' => null,
            'start_date' => null,
            'end_date' => null,
            'added_people' => null,
            'removed_people' => null
        ]);
        try {
            $activity = Activity::getValidActivityWithId($data['activity_id']);
            if ($data['activity_id'] > 0 && !empty($activity)) {
                if (!empty($data['activity_name']))
                    $activity->setName($data['activity_name']);
                if (!empty($data['target_group'])) {
                    $tg = TargetGroup::getValidTargetGroupWithId($data['target_group']);
                    if (!empty($tg))
                        $activity->setTargetGroup($tg);
                }
                if (!empty($data['target_group_comment']))
                    $activity->setTargetGroupComment($data['target_group_comment']);
                if (!empty($data['start_date'])) {
                    $start_date = new DateTime($data['start_date']);
                    $activity->setStartDate($start_date);
                }
                if (!empty($data['end_date'])) {
                    $end_date = new DateTime($data['end_date']);
                    $activity->setEndDate($end_date);
                }
                if (!empty($data['added_people'])) {
                    $r = $this->getPeopleEntitiesFromData($data['added_people']);
                    if (!empty($r['error']))
                        $response['error'] = $r['error'];
                    else
                        $activity->addPeople($r['people']);
                }
                if (!empty($data['removed_people'])) {
                    $r = $this->getPeopleEntitiesFromData($data['removed_people']);
                    if (!empty($r['error']))
                        $response['error'] = $r['error'];
                    else
                        $activity->removePeople($r['people']);
                }
                $this->writeActivityToDB($activity);
            }
        } catch (Exception $e) {
            $response['error'] = $this->getJSONError(2, 'Error while querying database for activity, message: ' . $e->getMessage());
        }
        //$response['error'] = $this->getJSONError(0, 'update not implemented.');
        return $response;
    }

    /**
     * @todo Consider putting this into person component
     * @param $data
     * @return mixed
     */
    private function getPeopleEntitiesFromData($data)
    {
        $arr = [];
        foreach($data as $person)
        {
            if(intval($person['p_id']) > 0){
                try{
                    $pEntity = Person::getValidPersonWithId($person['p_id']);
                    if(!empty($pEntity))
                    {
                        $arr[] = $pEntity;
                    }
                } catch (Exception $e) {
                    $ret['error'] = $this->getJSONError(5, 'Error while trying to find people');
                }
            }
        }
        if(count($arr) < count($data))
            $ret['error'] = $this->getJSONError(6, "could not get all people from ids");
        $ret['people'] = $arr;
        return $ret;
    }

    /**
     * @todo: Consider putting this into activity component
     * @todo: I doubt this code will ever get reused anywhere else, might as well put it back where it came from
     * @param $data
     * @param $bonus
     * @return ActivityEntity
     * @since 0.0.6
     */
    private function createActivityFromData($data, $bonus)
    {
        $user = Apollo::getInstance()->getUser();
        $activity = new ActivityEntity();
        $activity->setOrganisation($user->getOrganisation());
        $activity->setName($data['activity_name']);
        $start_date = new DateTime($data['start_date']);
        $end_date = new DateTime($data['end_date']);
        $activity->setStartDate($start_date);
        $activity->setEndDate($end_date);
        if($bonus) {
            if(!empty($bonus['people']))
                $activity->addPeople($bonus['people']);
            if(!empty($bonus['target_group']))
                $activity->setTargetGroup($bonus['target_group']);
            if(!empty($bonus['target_group_comment']))
                $activity->setTargetGroupComment($bonus['target_group_comment']);
        }
        return $activity;
    }

    /**
     * @param $activity
     */
    private function writeActivityToDB($activity)
    {
        $em = DB::getEntityManager();
        $em->persist($activity);
        $em->flush();
    }

    /**
     * Just accepts new people, adds them/deletes them from the activity
     * @return mixed
     */
    public function actionActivitySavePeople()
    {
        $response['error'] = null;
        $data = $this->parseRequest(['activity_id' => null, 'toAdd' => null, 'toDelete' => null]);
        if (!$this->areFieldsEmpty($data)) {
            $activity = Activity::getValidActivityWithId($data['activity_id']);
            if($activity) {
                foreach($data['toAdd'] as $person_id){
                    $person = Person::getValidPersonWithId($person_id);
                    if($person) {
                        $activity->addPerson($person);
                    }
                }
                foreach($data['toDelete'] as $person_id){
                    $person = Person::getValidPersonWithId($person_id);
                    if($person) {
                        $activity->removePerson($person);
                    }
                }
                try {
                    $this->writeActivityToDB($activity);
                } catch (Exception $e) {
                    $response['error'] = $this->getJSONError(4, 'Unexpected exception when saving the new data. Message: ' . $e->getMessage());
                }
            } else {
                $response['error'] = $this->getJSONError(3, 'Could not find activity with given id while saving people');
            }
        } else {
            $response['error'] = $this->getJSONError(1, 'Some of the fields are empty');
        }
        return $response;
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
     * For a bunch of fields, checks if any of them is empty. Returns true if at least one is empty
     * @param $data
     * @return bool
     */
    private function areFieldsEmpty($data)
    {
        foreach ($data as $key => $value) {
            if (empty($value))
                return true;
        }
        return false;
    }

    /**
     * @param ActivityEntity $activity
     * @return mixed
     */
    private function registerActivity($activity)
    {
        try {
            $this->writeActivityToDB($activity);
            $response['activity_id'] = $activity->getId();
        } catch (Exception $e) {
            $response['error'] = $this->getJSONError(2, $e->getMessage());
        }
        return $response;
    }

    /**
     * Just generates an error. Used for encapsulating an error
     * @param $id
     * @param $description
     * @return array
     */
    private function getJSONError($id, $description)
    {
        return [
            'id' => $id,
            'description' => $description
        ];
    }
}