<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Apollo;


/**
 * Class Field
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.2
 */
class Field extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\FieldEntity';

    public static function getFieldNames()
    {
        $organisation = Apollo::getInstance()->getUser()->getOrganisation();
        $fields = self::getRepository()->findBy(['is_hidden' => '0', 'organisation' => $organisation]);
        $names = [];
        foreach($fields as $field)
            $names[] = $field->getName();
        return $names;
    }

    public static function getDefaultsValues($field)
    {
        $defaults =  $field->getDefaults();
        $defaultArray = [];
        foreach ($defaults as $default) {
            $defaultArray[] = $default->getValue();
        }
        return $defaultArray;
    }
}