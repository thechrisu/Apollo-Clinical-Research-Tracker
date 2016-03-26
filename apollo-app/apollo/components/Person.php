<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;


/**
 * Class Person
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
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
     * @return $recentRecord
     * @since 0.0.6
     */
    public static function getMostRecentRecord($person_id)
    {
        $recentRecord = null;
        $recentDate = null;

        $people = Person::getRepository();
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
}