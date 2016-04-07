<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\Activity;
use Apollo\Components\DB;
use Apollo\Components\TargetGroup;
use Apollo\Components\Field;
use Apollo\Components\Person;
use Apollo\Components\Record;
use Apollo\Entities\ActivityEntity;
use Apollo\Entities\FieldEntity;
use Apollo\Entities\PersonEntity;
use Apollo\Entities\RecordEntity;
use Apollo\Entities\TargetGroupEntity;
use Doctrine\ORM\QueryBuilder;
use Doctrine;
use Exception;


/**
 * Class GetController
 *
 * Deals with get request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.1.2
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
        $people = $peopleQuery->getResult();
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
        }
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
     * TODO: Add description for function
     *
     * @param QueryBuilder $queryBuilder
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
     * @param PersonEntity $person
     * @return mixed
     */
    private function getFormattedPersonData($person)
    {
        $responsePerson = [];
        /** @var RecordEntity $recentRecord */
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
     * Returns an object containing the essential information of a person
     *
     * @param RecordEntity $record
     * @return array
     * @since 0.0.9
     */
    private function getInfoRecord($record)
    {
        /**
         * @var RecordEntity[] $other_records
         */
        $other_records = Record::getRepository()->findBy(['person' => $record->getPerson()->getId(), 'is_hidden' => 0]);
        $id_array = [];
        $name_array = [];
        foreach ($other_records as $other_record) {
            if ($other_record->getId() != intval($_GET['id'])) {
                $id_array[] = $other_record->getId();
                $name_array[] = $other_record->findVarchar(FIELD_RECORD_NAME);
            }
        }
        return [
            "given_name" => $record->getPerson()->getGivenName(),
            "middle_name" => $record->getPerson()->getMiddleName(),
            "last_name" => $record->getPerson()->getLastName(),
            "email" => $record->findVarchar(FIELD_EMAIL),
            "address" => $record->findMultiple(FIELD_ADDRESS),
            "phone" => $record->findVarchar(FIELD_PHONE),
            "awards" => $record->findMultiple(FIELD_AWARDS),
            "publications" => $record->findMultiple(FIELD_PUBLICATIONS),
            "start_date" => $record->findDateTime(FIELD_START_DATE)->format('Y-m-d H:i:s'),
            "end_date" => $record->findDateTime(FIELD_END_DATE)->format('Y-m-d H:i:s'),
            "person_id" => $record->getPerson()->getId(),
            "record_id" => $record->getId(),
            "record_name" => $record->findVarchar(FIELD_RECORD_NAME),
            "record_ids" => $id_array,
            "record_names" => $name_array
        ];
    }

    /**
     * Returns record data for viewing
     *
     * @since 0.0.3
     */
    public function actionRecordView()
    {
        $data = $this->parseRequest(['id' => 0]);
        $response['error'] = null;
        /**
         * @var RecordEntity $record
         */
        if ($data['id'] > 0 && ($record = Record::getRepository()->find($data['id'])) != null) {
            Record::prepare($record);
            $response['essential'] = $this->getInfoRecord($record);
            $fieldsViewData = [];
            $fieldRepo = Field::getRepository();
            /**
             * @var FieldEntity[] $fields
             */
            $fields = $fieldRepo->findBy(['is_essential' => false, 'is_hidden' => false, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
            foreach ($fields as $field) {
                $fieldViewData['name'] = $field->getName();
                $fieldViewData['type'] = $field->getType();
                $defaults = $field->getDefaults();
                $defaultArray = [];
                foreach ($defaults as $default) {
                    $defaultArray[] = $default->getValue();
                }
                $fieldData = $record->findOrCreateData($field->getId());
                $value = [];
                if ($field->hasDefault()) {
                    if (!$field->isMultiple()) {
                        if ($fieldData->isDefault() || !$field->isAllowOther()) {
                            $value = $defaultArray[$fieldData->getInt()];
                        } else {
                            $value = $fieldData->getVarchar();
                        }
                    } else {
                        $selected = unserialize($fieldData->getLongText());
                        $fieldViewData['type'] = 2;
                        if (count($selected) > 0) {
                            for ($i = 0; $i < count($selected); $i++) {
                                $value[] = $defaultArray[intval($selected[$i])];
                            }
                        } else {
                            $value = '';
                        }
                    }
                } else if ($field->isMultiple()) {
                    $value = unserialize($fieldData->getLongText());
                } else {
                    switch ($field->getType()) {
                        case 1:
                            $value = $fieldData->getInt();
                            break;
                        case 2:
                            $value = $fieldData->getVarchar();
                            break;
                        case 3:
                            $value = $fieldData->getDateTime()->format('Y-m-d H:i:s');
                            break;
                        case 4:
                            $value = $fieldData->getLongText();
                            break;
                    }
                }
                $fieldViewData['value'] = $value;
                $fieldsViewData[] = $fieldViewData;
            }
            $response['data'] = $fieldsViewData;
        } else {
            $response['error'] = ['id' => 1, 'description' => 'The supplied ID is invalid!'];
        }
        echo json_encode($response);
    }

    /**
     * Returns JSON containing information used to build the edit view for a record
     *
     * @since 0.0.9
     */
    public function actionRecordEdit()
    {
        $data = $this->parseRequest(['id' => 0]);
        $response['error'] = null;
        /**
         * @var RecordEntity $record
         */
        if ($data['id'] > 0 && ($record = Record::getRepository()->find($data['id'])) != null) {
            Record::prepare($record);
            $response['essential'] = $this->getInfoRecord($record);
            $fieldsEditData = [];
            $fieldRepo = Field::getRepository();
            /**
             * @var FieldEntity[] $fields
             */
            $fields = $fieldRepo->findBy(['is_essential' => false, 'is_hidden' => false, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
            foreach ($fields as $field) {
                $fieldEditData['id'] = $field->getId();
                $fieldEditData['name'] = $field->getName();
                $fieldEditData['type'] = $field->getType();
                $fieldEditData['has_default'] = $field->hasDefault();
                $fieldEditData['allow_other'] = $field->isAllowOther();
                $fieldEditData['is_multiple'] = $field->isMultiple();
                $defaults = $field->getDefaults();
                $defaultArray = [];
                foreach ($defaults as $default) {
                    $defaultArray[] = $default->getValue();
                }
                $fieldEditData['defaults'] = $defaultArray;
                $fieldData = $record->findOrCreateData($field->getId());
                $value = [];
                if ($field->hasDefault()) {
                    if($field->isMultiple()) {
                        $value = unserialize($fieldData->getLongText());
                    } else {
                        if ($fieldData->isDefault() || !$field->isAllowOther()) {
                            $value = $fieldData->getInt();
                        } else {
                            $value = $fieldData->getVarchar();
                        }
                    }
                } else if ($field->isMultiple()) {
                    $value = unserialize($fieldData->getLongText());
                } else {
                    switch ($field->getType()) {
                        case 1:
                            $value = $fieldData->getInt();
                            break;
                        case 2:
                            $value = $fieldData->getVarchar();
                            break;
                        case 3:
                            $value = $fieldData->getDateTime()->format('Y-m-d H:i:s');
                            break;
                        case 4:
                            $value = $fieldData->getLongText();
                            break;
                    }
                }
                $fieldEditData['value'] = $value;
                $fieldsEditData[] = $fieldEditData;
            }
            $response['data'] = $fieldsEditData;
        } else {
            $response['error'] = ['id' => 1, 'description' => 'The supplied ID is invalid!'];
        }
        echo json_encode($response);
    }

    /**
     * It returns short information about several activities
     * Currently serves dummy data
     *
     * @since 0.0.5
     * TODO: Serve real data
     */
    public function actionActivities()
    {

        $data = $this->parseRequest(['page' => 1, 'sort' => 1, 'search' => null]);
        $page = $data['page'] > 0 ? $data['page'] : 1;
        $activities = null;
        try{
            $activityQB = $this->createQueryForActivitiesRequest($data);
            $activityQuery = $activityQB->getQuery();
            $activities =  $activityQuery->getResult();
        } catch (Exception $e) {

            $response['error'] = ['id' => 3, 'description' => "Unable to get data from database, server issue? (error in query): " . $e->getMessage()];
            echo json_encode($response);
        } finally {
            if (!empty($activities)) {
                $response = $this->getFormattedActivities($activities, $page);
                echo json_encode($response);
            } else {
                $response['error'] = ['id' => 4, 'description' => "Unable to get data from database, server issue? (error in fetching)"];
            }
        }

    }

    /**
     * @param $data
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryForActivitiesRequest($data)
    {
        $activityQB = $this->getQueryValidActivities('a');
        if (!empty($data['search'])) {
            $this->addActivitySearch($activityQB, $data['search']);
            return $activityQB;
        }
        return $activityQB;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param $search
     */
    private function addActivitySearch($queryBuilder, $search)
    {
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('a.name', ':search'),
            $queryBuilder->expr()->like('a.target_group_comment', ':search')
        ));
        $queryBuilder->setParameter('search', '%' . $search . '%');
    }

    private function getFormattedActivities($activities, $page)
    {
        $response['error'] = null;
        $response['count'] = count($activities);
        for ($i = 10 * ($page - 1); $i < min($response['count'], $page * 10); $i++) {
            $activity = $activities[$i];
            $response['activities'][] = $this->getFormattedShortActivityData($activity);
        }
        return $response;
    }

    private function getFormattedShortActivityData($activity)
    {
        $responseActivity = [
            'id' => $activity->getId(),
            'name' => $activity->getName(),
            'start_date' => $activity->getStartDate()->format('Y-m-d H:i:s'),
            'end_date' => $activity->getEndDate()->format('Y-m-d H:i:s')
        ];
        return $responseActivity;
    }

    /**
     * Returns detailed information on one activity
     * @since 0.0.6
     */
    public function actionActivity()
    {
        $data = $this->parseRequest(['id' => 0]);
        $activity = Activity::getRepository()->findOneBy(['id' => $data['id'], 'is_hidden' => 0, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);

        if ($data['id'] > 0 && $activity != null)
        {
            $activityInfo = $this->getInfoActivity($activity);
            //TODO: Check if there is more than one activity. If so, then report that as invalid
            $response = $activityInfo;
        } else {
            $response['error'] = $this->getJSONError(1, 'The supplied id is invalid!');
        }
        echo json_encode($response);
    }

    /**
     * Formats an activity as a valid JSON object
     * @param ActivityEntity $activity
     * @return array
     */
    private function getInfoActivity(ActivityEntity $activity)
    {
        $people = $this->formatPeopleShortConcatName($activity->getPeople());
        $activityInfo = [
            'error' => null,
            'id' => $activity->getId(),
            'name' => $activity->getName(),
            'target_groups' => $this->getFormattedTargetGroups($activity->getTargetGroup()),
            'target_group_comment' => $activity->getTargetGroupComment(),
            'start_date' => $activity->getStartDate()->format('Y-m-d H:i:s'),
            'end_date' => $activity->getEndDate()->format('Y-m-d H:i:s'),
            'participants' => $people
        ];
        return $activityInfo;
    }

    /**
     * @param $activity_activeTarget
     * @return array
     */
    private function getFormattedTargetGroups($activity_activeTarget)
    {
        $targetGroups = $this->getValidTargetGroups();
        $arr = [];
        foreach($targetGroups as $targetGroup)
        {
            $tg = $this->getFormattedTargetGroup($targetGroup);
            $arr[] = $tg;
        }
        $ret['data'] = $arr;
        $ret['active'] = $this->getFormattedTargetGroup($activity_activeTarget);
        return $ret;
    }

    /**
     * @param $targetGroup
     * @return array
     */
    private function getFormattedTargetGroup($targetGroup)
    {
        $tg = [
            'id' => $targetGroup->getId(),
            'name' => $targetGroup->getName()
        ];
        return $tg;
    }

    /**
     * Gets all the people not in the specified activity, who meet the search criteria and who are not already temporarily added
     */
    public function actionActivityPeople()
    {
        $data = $this->parseRequest(['activity_id' => null, 'temporarily_added' => null, 'search' => null]);
        $people = null;
        $pqb = $this->getQueryForPeopleNotInProgramme($data);
        $activity = Activity::getRepository()->findBy(['id' => $data['activity_id'], 'is_hidden' => 0, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
        if((empty($activity[0])) || $activity[0]->isHidden()){
            $response['error'] = $this->getJSONError(2, "Activity hidden.");
        } else {
            try {
                $response['error'] = null;
                $query = $pqb->getQuery();
                $result = $query->getResult();
                $response['data'] = $this->formatPeopleShortConcatName($result);
            } catch (Exception $e) {
                $response['error'] = $this->getJSONError(1, "Query failed with exception " . $e->getMessage());
            }
        }
        echo json_encode($response);
    }

    /**
     * @param $people
     * @return array
     */
    private function formatPeopleShortConcatName($people)
    {
        $people_obj = [];
        foreach($people as $person) {
                $person_obj = [
                    'id' => $person->getId(),
                    'name' => implode(' ', [$person->getGivenName(), $person->getMiddleName(),$person->getLastName()])
                ];
            $people_obj[] = $person_obj;
        }
        return $people_obj;
    }

    /**
     * @param $queryBuilder
     * @param $search
     */
    private function addShortPersonSearch($queryBuilder, $search)
    {
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('p.given_name', ':search'),
            $queryBuilder->expr()->like('p.middle_name', ':search'),
            $queryBuilder->expr()->like('p.last_name', ':search')
        ));
        $queryBuilder->setParameter('search', '%' . $search . '%');
    }

    /**
     * @param $queryBuilder
     * @param $array
     */
    private function removePeopleInArray($queryBuilder, $array)
    {
        foreach ($array as $id) {

            $queryBuilder->andWhere(
                $queryBuilder->expr()->notIn('p.id', $id)
            );
        }
    }

    /**
     * @param $pqb
     * @param $aqb
     */
    private function getPeopleNotInTable($pqb, $aqb)
    {
        $pqb->andWhere(
            $pqb->expr()->notIn('p.id', $aqb->getDQL())
        );
    }

    /**
     * TODO: Fix bug: When activity is hidden, no people should be returned
     * @param $data
     * @return QueryBuilder
     */
    private function getQueryForPeopleNotInProgramme($data)
    {
        $aqb = $this->getQueryPeopleIdWithValidActivityId($data);
        $pqb = $this->getQueryValidPeople();
        if (!empty($data['temporarily_added'])) {
            $this->removePeopleInArray($pqb, $data['temporarily_added']);
        }
        if (!empty($data['search'])) {
            $this->addShortPersonSearch($pqb, $data['search']);
        }

        $this->getPeopleNotInTable($pqb, $aqb);
        return $pqb;
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

    /**
     * @param $people
     * @return array
     */
    private function formatPeopleShort($people)
    {
        $people_obj = [];
        foreach($people as $person) {
            $person_obj = [
                'id' => $person->getId(),
                'given_name' => $person->getGivenName(),
                'last_name' => $person->getLastName()
            ];
            $people_obj[] = $person_obj;
        }
        return $people_obj;
    }

    /**
     * Given an activity id, returns a query bulider that contains the ids of the people in the activity, as long as the activity is valid
     * @param $data
     * @return QueryBuilder
     */
    private function getQueryPeopleIdWithValidActivityId($data)
    {
        $em = DB::getEntityManager();
        $activityRepo = $em->getRepository(Activity::getEntityNamespace());
        $activityQB = $activityRepo->createQueryBuilder('a');
        $activityQB->select('ppl.id');
        $organisation_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $notHidden = $activityQB->expr()->eq(strtolower('a' . '.is_hidden'), '0');
        $sameOrgId = $activityQB->expr()->eq('a' . '.organisation', $organisation_id);
        $valid = $activityQB->expr()->andX($notHidden, $sameOrgId);
        $matchesId = $activityQB->expr()->eq('a.id', $data['activity_id']);
        $cond = $activityQB->expr()->andX($valid, $matchesId);

        $activityQB->join('a.' . 'people', 'ppl', 'WHERE', $cond);
        return $activityQB;
    }

    /**
     * @return array
     */
    private function getValidTargetGroups()
    {
        $org_id = Apollo::getInstance()->getUser()->getOrganisationId();
        return TargetGroup::getRepository()->findBy(['organisation' => $org_id, 'is_hidden' => 0]);
    }

    /**
     * @param $alias
     * @return QueryBuilder
     */
    private function getQueryValidActivities($alias)
    {

        $em = DB::getEntityManager();
        $activityRepo = $em->getRepository(Activity::getEntityNamespace());
        $activityQB = $activityRepo->createQueryBuilder($alias);
        $organisation_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $notHidden = $activityQB->expr()->eq($alias . '.is_hidden', '0');
        $sameOrgId = $activityQB->expr()->eq($alias . '.organisation', $organisation_id);
        $cond = $activityQB->expr()->andX($notHidden, $sameOrgId);
        $activityQB->where($cond);
        return $activityQB;
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryValidPeople()
    {
        $em = DB::getEntityManager();
        $peopleRepo = $em->getRepository(Person::getEntityNamespace());
        $organisation_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $pqb = $peopleRepo->createQueryBuilder('p');
        $pqb->where(
            $pqb->expr()->andX(
                $pqb->expr()->eq('p.organisation', $organisation_id),
                $pqb->expr()->eq('p.is_hidden', '0')
            )
        );
        return $pqb;
    }

    private function getJSONError($id, $description) {
        return  [
            'id' => $id,
            'description' => $description
        ];
    }
}