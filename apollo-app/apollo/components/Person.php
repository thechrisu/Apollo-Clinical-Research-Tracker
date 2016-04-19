<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Apollo;
use Apollo\Entities\PersonEntity;
use Apollo\Entities\RecordEntity;


/**
 * Class Person
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.4
 */
class Person extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\PersonEntity';

    /**
     * For a given person, returns its current record
     * @param $person_id
     * @return RecordEntity $recentRecord
     * @since 0.0.6
     */
    public static function getMostRecentRecord($person_id)
    {
        $recentRecord = null;
        $recentDate = null;

        $people = Person::getRepository();
        /** @var PersonEntity $person */
        $person = $people->find($person_id);

        foreach ($person->getRecords() as $currentRecord) {
            if (!$currentRecord->isHidden()) {
                $currentDate = $currentRecord->findDateTime(FIELD_START_DATE);
                if ($recentDate == null || $recentDate < $currentDate) {
                    $recentDate = $currentDate;
                    $recentRecord = $currentRecord;
                }
            }
        }
        return $recentRecord;
    }

    /**
     * Consider putting this into Activity component
     * @param PersonEntity $person
     * @return string[]
     */
    public static function getActivityNames($person)
    {
        $ret = [];
        foreach ($person->getActivities() as $activity) {
            if(!$activity->isHidden())
                $ret[] = $activity->getName();
        }
        return $ret;
    }

    /**
     * @param $id
     * @return PersonEntity|null
     */
    public static function getValidPersonWithId($id)
    {
        $org = Apollo::getInstance()->getUser()->getOrganisation();
        $people = Person::getRepository();
        $person = $people->find($id);
        if(!empty($person) && !$person->isHidden() && $person->getOrganisation() == $org){
            return $person;
        } else {
            return null;
        }
    }

    /**
     * Given a person (retrieved from the data base), returns an object containing all the data of the person
     * @param PersonEntity $person
     * @return mixed
     */
    public static function getFormattedData($person)
    {
        $responsePerson = [];
        /** @var RecordEntity $recentRecord */
        $recentRecord = self::getMostRecentRecord($person->getId());
        $responsePerson['id'] = $recentRecord->getId();
        $responsePerson['given_name'] = $person->getGivenName();
        $responsePerson['last_name'] = $person->getLastName();
        $responsePerson['phone'] = $recentRecord->findVarchar(FIELD_PHONE);
        $responsePerson['email'] = $recentRecord->findVarchar(FIELD_EMAIL);
        return $responsePerson;
    }

    /**
     * Given a person, this returns formatted information about the activities of the person
     * @param PersonEntity $person
     * @return array
     */
    public static function getFormattedActivitiesOfPerson($person)
    {
        /** @var PersonEntity $person */
        $activities = $person->getActivities();
        $ret = [];
        foreach($activities as $activity){
            if(!$activity->isHidden())
                $ret[] = Activity::getFormattedShortData($activity);
        }
        return $ret;
    }

    /**
     * For a given number of people, return a formatted object of the people's record data
     * @param PersonEntity[] $people
     * @param $page
     * @return mixed
     */
    public static function getFormattedRecordsOfPeople($people, $page)
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
                $response['data'][] = self::getFormattedData($person);
            }
        }
        return $response;
    }

    /**
     * Returns people's id and their name together with their most recent record id
     * @param PersonEntity[] $people
     * @return array
     */
    public static function getFormattedPeopleShortWithRecords($people)
    {
        $people_obj = [];
        foreach ($people as $person) {
            $p_id = $person->getId();
            $person_obj = [
                'p_id' => $p_id,
                'r_id' => self::getMostRecentRecord($p_id)->getId(),
                'name' => implode(' ', [$person->getGivenName(), $person->getMiddleName(), $person->getLastName()])
            ];
            $people_obj[] = $person_obj;
        }
        return $people_obj;
    }

}