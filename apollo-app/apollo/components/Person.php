<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
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
 * @version 0.0.3
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
    public static function getValidPerson($id)
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

}