<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Apollo;
use Apollo\Entities\FieldEntity;
use Apollo\Entities\RecordEntity;
use Exception;


/**
 * Class Record
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.5
 */
class Record extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\RecordEntity';

    /**
     * Checks that default data exists for all fields, if not, adds the missing fields.
     *
     * @param RecordEntity $record
     * @since 0.0.3 Now uses serialization correctly
     * @since 0.0.2
     */
    public static function prepare($record) {
        $fieldRepo = Field::getRepository();
        /**
         * @var FieldEntity[] $fields
         */
        $fields = $fieldRepo->findBy(['is_hidden' => false, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
        /**
         * @var FieldEntity $field
         */
        foreach($fields as $field) {
            $record->findOrCreateData($field->getId());
        }
    }

    /**
     * Given a person id, this returns all of their valid records
     * @todo Put this in Person.php?
     * @param $person_id
     * @return array
     */
    public static function getValidRecordsOfPerson($person_id) {
        return self::getRepository()->findBy(['person' => $person_id, 'is_hidden' => 0]);
    }

    /**
     * Returns a RecordEntity for an id
     * @param $record_id
     * @return RecordEntity|null
     */
    public static function getValidRecordWithId($record_id) {
        try{
            $record = Record::getRepository()->findOneBy(['is_hidden' => false, 'id' => $record_id]);
            if(!empty($record))
                return $record;
            else
                return null;
        } catch (Exception $e) {
            return null;
        }

    }

    /**
     * Returns an array of all the fields of the record
     * @param RecordEntity $record
     * @param $is_essential
     * @return array
     */
    public static function getFormattedFields($record, $is_essential) {
        $fieldsData = [];
        $fieldRepo = Field::getRepository();
        $fields = $fieldRepo->findBy(['is_essential' => $is_essential, 'is_hidden' => false, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
        foreach ($fields as $field) {
            $fieldData = self::getFormattedField($record, $field);
            $fieldsData[] = $fieldData;
        }
        return $fieldsData;
    }


    /**
     * Returns an object containing the information about a record
     * @param RecordEntity $record
     * @return array
     * @since 0.0.9
     */
    public static function getFormattedData($record)
    {
        /**
         * @var RecordEntity[] $other_records
         */
        $person = $record->getPerson();
        $other_records = self::getValidRecordsOfPerson($person->getId());

        $id_array = [];
        $name_array = [];
        foreach ($other_records as $other_record) {
            if ($other_record->getId() != intval($_GET['id'])) {
                $id_array[] = $other_record->getId();
                $name_array[] = $other_record->findVarchar(FIELD_RECORD_NAME);
            }
        }
        return [
            "given_name" => $person->getGivenName(),
            "middle_name" => $person->getMiddleName(),
            "last_name" => $person->getLastName(),
            "email" => $record->findVarchar(FIELD_EMAIL),
            "address" => $record->findMultiple(FIELD_ADDRESS),
            "phone" => $record->findVarchar(FIELD_PHONE),
            "awards" => $record->findMultiple(FIELD_AWARDS),
            "publications" => $record->findMultiple(FIELD_PUBLICATIONS),
            "start_date" => $record->findDateTime(FIELD_START_DATE)->format('Y-m-d H:i:s'),
            "end_date" => $record->findDateTime(FIELD_END_DATE)->format('Y-m-d H:i:s'),
            "person_id" => $person->getId(),
            "record_id" => $record->getId(),
            "record_name" => $record->findVarchar(FIELD_RECORD_NAME),
            "record_ids" => $id_array,
            "record_names" => $name_array,
            "activities" => Person::getFormattedActivitiesOfPerson($person)
        ];
    }

    /**
     * @param RecordEntity $record
     * @param FieldEntity $field
     * @return mixed
     */
    public static function getFormattedField($record, $field)
    {
        $fieldData = $record->findOrCreateData($field->getId());
        return Data::getFormattedData($fieldData);
    }

    /**
     * @param $record_ids
     * @return array
     */
    public static function getPeopleFromRecordIds($record_ids)
    {
        $people = [];
        foreach($record_ids as $id){
            $temp = self::getValidRecordWithId($id);
            if(!empty($temp))
                $people[] = $temp->getPerson();
        }
        return $people;
    }
}