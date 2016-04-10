<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;


/**
 * Class Data
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class Data extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\DataEntity';

    public static function getStringArray($datae)
    {
        $ret = [];
        foreach($datae as $data)
        {
            $ret[] = self::serialize($data);
        }
        return $ret;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function getFormattedData($data)
    {
        $field = $data->getField();
        $ret['name'] = $field->getName();
        $ret['type'] = $field->getType();
        $ret['value'] = self::getDataValue($data);
        return $ret;
    }

    /**
     * @param $data
     * @return mixed
     */
    private static function getDataValue($data)
    {
        $field = $data->getField();
        $value = [];
        if ($field->hasDefault()) {
            if (!$field->isMultiple()) {
                if ($data->isDefault() || !$field->isAllowOther()) {
                    $value = self::getDefaultValue($data);
                } else {
                    $value = $data->getVarchar();
                }
            } else {
                $ret['type'] = 2;
                $value = self::getMultiple($data);
            }
        } else if ($field->isMultiple()) {
            $value = unserialize($data->getLongText());
        } else {
            $value = self::serialize($data);
        }
        return $value;
    }

    public static function serialize($data)
    {
        $field = $data->getField();
        switch($field->getType())
        {
            case 1://Integer
                return $data->getInt();
                break;
            case 2://Varchar
                return $data->getVarchar();
                break;
            case 3://Date
                return $data->getDateTime()->format('Y-m-d H:i:s');
                break;
            case 4://Long text
                return $data->getLongText();
                break;
            default:
                return "errror: Unknown field type";
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    private static function getDefaultValue($data)
    {
        $field = $data->getField();
        $defaultArray = Field::getDefaultsValues($field);
        return $defaultArray[$data->getInt()];
    }

    /**
     * @param $data
     * @return array|string
     */
    private static function getMultiple($data)
    {
        $field = $data->getField();
        $defaultArray = Field::getDefaultsValues($field);
        $value = '';
        $selected = unserialize($data->getLongText());
        if (count($selected) > 0) {
            for ($i = 0; $i < count($selected); $i++) {
                $value[] = $defaultArray[intval($selected[$i])];
            }
        }
        return $value;
    }

    private static function concatMultiple($values)
    {
        return implode("; ", $values);
    }

    private static function concatMultipleDates($dates)
    {
        $values = [];
        foreach($dates as $date)
        {
            $values[] = $date->format('Y-m-d');
        }
        return self::concatMultiple($values);
    }
}