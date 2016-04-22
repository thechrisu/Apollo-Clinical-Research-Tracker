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
use Apollo\Components\Data;
use Apollo\Components\ExcelExporter;
use Apollo\Components\Field;
use Apollo\Components\Person;
use Apollo\Components\Record;
use Apollo\Entities\FieldEntity;
use Apollo\Entities\RecordEntity;
use Doctrine\ORM\QueryBuilder;
use Doctrine;
use Exception;


/**
 * Class GetController
 *
 * Deals with get request to the API
 * @todo: Extract queries to other components
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.1.6
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
     * @todo: Contemplate over putting extracted functions somewhere else
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
        $response = Person::getFormattedRecordsOfPeople($people, $page);
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
     * Adds the search of a person to a given query
     *
     * @param QueryBuilder $queryBuilder
     * @param string $search
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
        if ($data['id'] > 0 && ($record = Record::getValidRecordWithId($data['id'])) != null) {
            Record::prepare($record);
            $response['essential'] = Record::getFormattedData($record);
            $fieldsViewData = Record::getFormattedFields($record, false);
            $response['data'] = $fieldsViewData;
        } else {
            $response['error'] = ['id' => 1, 'description' => 'The supplied ID is invalid!'];
        }
        echo json_encode($response);
    }

    public function actionPeopleExcel()
    {
        //$data = $this->parseRequest(['ids' => null]);
        $ee = new ExcelExporter();
        /*if(!empty($data['ids']))
            $ee->getDataFromRecordIds($data['ids']);
        else*/
        $ee->downloadAllRecords();
        //$ee->getTestFile();
    }

    /**
     * Returns JSON containing information used to build the edit view for a record
     * @todo: Put all of the field formatting in the field component
     * @since 0.0.9
     */
    public function actionRecordEdit()
    {
        $data = $this->parseRequest(['id' => 0]);
        $response['error'] = null;
        /**
         * @var RecordEntity $record
         */
        if ($data['id'] > 0 && ($record = Record::getValidRecordWithId($data['id']))) {
            Record::prepare($record);
            $response['essential'] = Record::getFormattedData($record);
            $fieldsEditData = [];
            /**
             * @var FieldEntity[] $fields
             */
            $fields = Field::getValidFields();
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
                if ($field->hasDefault()) {
                    if ($field->isMultiple()) {
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
                    $value = Data::serialize($fieldData);
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
     *
     * @since 0.0.5
     */
    public function actionActivities()
    {

        $data = $this->parseRequest(['page' => 1, 'sort' => 1, 'search' => null]);
        $page = $data['page'] > 0 ? $data['page'] : 1;
        $activities = null;
        try {
            $activityQB = $this->createQueryForActivitiesRequest($data);
            $activityQuery = $activityQB->getQuery();
            $activities = $activityQuery->getResult();
        } catch (Exception $e) {

            $response['error'] = ['id' => 3, 'description' => "Unable to get data from database, server issue? (error in query): " . $e->getMessage()];
            echo json_encode($response);
        } finally {
            $response = Activity::getFormattedActivities($activities, $page);
            echo json_encode($response);
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

    /**
     * Returns detailed information on one activity
     * @since 0.0.6
     */
    public function actionActivity()
    {
        $data = $this->parseRequest(['id' => 0]);
        $activity = Activity::getValidActivityWithId($data['id']);

        if ($data['id'] > 0 && $activity != null) {
            $activityInfo = Activity::getFormattedData($activity);
            $response = $activityInfo;
        } else {
            $response['error'] = $this->getJSONError(1, 'The supplied id is invalid!');
        }
        echo json_encode($response);
    }

    /**
     * Gets all the people not in the specified activity, who meet the search criteria and who are not already temporarily added
     * @todo only return like the top ten results (in order to speed up the lookup)
     */
    public function actionActivityPeople()
    {
        $data = $this->parseRequest(['activity_id' => null, 'temporarily_added' => null, 'search' => null]);
        $people = null;
        $pqb = $this->getQueryForPeopleNotInProgramme($data);
        $pqb->setMaxResults(10);
        if ((empty(Activity::getValidActivityWithId($data['activity_id'])))) {
            $response['error'] = $this->getJSONError(2, "Activity hidden.");
        } else {
            try {
                $response['error'] = null;
                $query = $pqb->getQuery();
                $result = $query->getResult();
                $response['data'] = Person::getFormattedPeopleShortWithRecords($result);
            } catch (Exception $e) {
                $response['error'] = $this->getJSONError(1, "Query failed with exception " . $e->getMessage());
            }
        }
        echo json_encode($response);
    }

    /**
     * @param QueryBuilder $queryBuilder
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
     * @param QueryBuilder $queryBuilder
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
     * @param QueryBuilder $pqb
     * @param QueryBuilder $aqb
     */
    private function getPeopleNotInTable($pqb, $aqb)
    {
        $pqb->andWhere(
            $pqb->expr()->notIn('p.id', $aqb->getDQL())
        );
    }

    /**
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
     * Given an activity id, returns a query builder that contains the ids of the people in the activity, as long as the activity is valid
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

    /**
     * Returns a list of all fields belonging to user's organisation
     *
     * @since 0.1.3
     */
    public function actionFields()
    {
        $em = DB::getEntityManager();
        $fieldRepo = $em->getRepository(Field::getEntityNamespace());
        /** @var FieldEntity[] $fields */
        $fields = $fieldRepo->findBy(['organisation' => Apollo::getInstance()->getUser()->getOrganisationId(), 'is_hidden' => false]);
        $response['error'] = null;
        $data = [];
        for ($i = 0; $i < count($fields); $i++) {
            $field = $fields[$i];
            $fieldData = [];
            $fieldData['id'] = $field->getId();
            $fieldData['essential'] = $field->isEssential();
            $fieldData['name'] = $field->getName();
            $fieldData['type'] = $field->getType();
            $subtype = 0;
            if($field->getType() == 2) {
                if($field->hasDefault()) {
                    if($field->isAllowOther()) {
                        $subtype = 4;
                    } elseif($field->isMultiple()) {
                        $subtype = 5;
                    } else {
                        $subtype = 3;
                    }
                } else {
                    if($field->isMultiple()) {
                        $subtype = 2;
                    } else {
                        $subtype = 1;
                    }
                }
            }
            $fieldData['subtype'] = $subtype;
            $defaults = $field->getDefaults();
            $defaultsData = [];
            for ($k = 0; $k < count($defaults); $k++) {
                $defaultsData[] = $defaults[$k]->getValue();
            }
            $fieldData['defaults'] = $defaultsData;
            $data[] = $fieldData;
        }
        $response['data'] = array_reverse($data);
        echo json_encode($response);
    }

    /**
     * Just generates an error. Used for encapsulating an error
     * @todo: Generate controller for all api-stuff, put this in there
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