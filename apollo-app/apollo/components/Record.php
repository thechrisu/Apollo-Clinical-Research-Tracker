<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Apollo;
use Apollo\Entities\FieldEntity;
use Apollo\Entities\RecordEntity;


/**
 * Class Record
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.3
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

    public static function getFormattedFields($record) {
        $fieldsData = [];
        $fieldRepo = Field::getRepository();
        $fields = $fieldRepo->findBy(['is_essential' => false, 'is_hidden' => false, 'organisation' => Apollo::getInstance()->getUser()->getOrganisationId()]);
        foreach ($fields as $field) {
            $fieldData = self::getFormattedFieldOfRecord($record, $field);
            $fieldsData[] = $fieldData;
        }
        return $fieldsData;
    }

    /**
     * @param $record
     * @param $field
     * @return mixed
     */
    private static function getFormattedFieldOfRecord($record, $field)
    {
        $fieldData = $record->findOrCreateData($field->getId());
        return Data::getFormattedData($fieldData);
    }
}