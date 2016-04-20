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
 * @version 0.0.4
 */
class Field extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\FieldEntity';

    public static function getValidFieldWithId($id)
    {
        $repo = self::getRepository();
        $org = Apollo::getInstance()->getUser()->getOrganisation();
        return $repo->findOneBy(['id' => $id, 'organisation' => $org]);
    }

    public static function getValidFields()
    {
        $repo = self::getRepository();
        $org_id = Apollo::getInstance()->getUser()->getOrganisationId();
        return $repo->findBy(['is_essential' => false, 'is_hidden' => false, 'organisation' => $org_id]);
    }

    public static function getFieldNames()
    {
        $organisation = Apollo::getInstance()->getUser()->getOrganisation();
        $fields = self::getRepository()->findBy(['is_hidden' => false, 'organisation' => $organisation]);
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