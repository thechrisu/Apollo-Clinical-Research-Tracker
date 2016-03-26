<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Apollo\Entities;
use Apollo\Apollo;
use Apollo\Components\Data;
use Apollo\Components\DB;
use Apollo\Components\Field;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
/**
 * Class RecordEntity
 *
 * @package Apollo\Entities
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.9
 * @Entity @Table("records")
 */
class RecordEntity
{
    /**
     * Unique ID of the record
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;
    /**
     * The person the record belongs to
     * @ManyToOne(targetEntity="PersonEntity", inversedBy="records")
     * @var PersonEntity
     */
    protected $person;
    /**
     * @OneToMany(targetEntity="DataEntity", mappedBy="record")
     * @OrderBy({"id" = "ASC"})
     * @var DataEntity[]
     */
    protected $data = null;
    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $created_on;
    /**
     * @ManyToOne(targetEntity="UserEntity")
     * @var UserEntity
     */
    protected $created_by;
    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $updated_on;
    /**
     * @ManyToOne(targetEntity="UserEntity")
     * @var UserEntity
     */
    protected $updated_by;
    /**
     * Determines if record is shown or not
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_hidden = false;
    /**
     * RecordEntity constructor.
     *
     * @param UserEntity $user
     * @since 0.0.6
     */
    public function __construct($user)
    {
        $this->created_by = $user;
        $this->created_on = new DateTime();
        $this->updated_by = $user;
        $this->updated_on = new DateTime();
    }
    /**
     * Finds int data
     *
     * @param int $field_id
     * @return int
     * @since 0.0.6
     */
    public function findInt($field_id)
    {
        $data = $this->findOrCreateData($field_id);
        return $data->getInt();
    }
    /**
     * Finds varchar data
     *
     * @param int $field_id
     * @return string
     * @since 0.0.6
     */
    public function findVarchar($field_id)
    {
        $data = $this->findOrCreateData($field_id);
        return $data->getVarchar();
    }
    /**
     * Finds multiple data
     *
     * @param int $field_id
     * @return mixed[]
     * @since 0.0.8
     */
    public function findMultiple($field_id)
    {
        $data = $this->findOrCreateData($field_id);
        return unserialize($data->getLongText());
    }
    /**
     * Finds DateTime data
     *
     * @param int $field_id
     * @return DateTime
     * @since 0.0.6
     */
    public function findDateTime($field_id)
    {
        $data = $this->findOrCreateData($field_id);
        return $data->getDateTime();
    }
    /**
     * Finds long text data
     *
     * @param int $field_id
     * @return string
     * @since 0.0.6
     */
    public function findLongText($field_id)
    {
        $data = $this->findOrCreateData($field_id);
        return $data->getLongText();
    }
    /**
     * Sets int value
     *
     * @param int $field_id
     * @param int $int
     * @since 0.0.6
     */
    public function setInt($field_id, $int)
    {
        $data = $this->findOrCreateData($field_id);
        $data->setInt($int);
    }
    /**
     * Sets varchar value
     *
     * @param int $field_id
     * @param string $name
     * @since 0.0.6
     */
    public function setVarchar($field_id, $name)
    {
        $data = $this->findOrCreateData($field_id);
        $data->setVarchar($name);
    }

    /**
     * Sets multiple varchar values
     *
     * @param int $field_id
     * @param mixed[] %data
     * @since 0.0.8
     */
    public function setMultiple($field_id, $data)
    {
        $this->setLongText($field_id, serialize($data));
    }

    /**
     * Sets DateTime value
     *
     * @param int $field_id
     * @param DateTime $date
     * @since 0.0.6
     */
    public function setDateTime($field_id, $date)
    {
        $data = $this->findOrCreateData($field_id);
        $data->setDateTime($date);
    }
    /**
     * Sets long text value
     *
     * @param int $field_id
     * @param string $text
     * @since 0.0.6
     */
    public function setLongText($field_id, $text)
    {
        $data = $this->findOrCreateData($field_id);
        $data->setLongText($text);
    }
    /**
     * Returns the data field or creates it if it does not exist
     *
     * @param int $field_id
     * @return DataEntity
     * @since 0.0.7 Now takes into account the type of the field
     * @since 0.0.6
     */
    public function findOrCreateData($field_id)
    {
        /**
         * @var FieldEntity $field
         */
        $field = Field::getRepository()->find($field_id);
        $data = Data::getRepository()->findOneBy(['record' => $this->getId(), 'field' => $field_id]);
        if ($data == null) {
            $data = new DataEntity();
            $data->setRecord($this);
            $data->setField($field);
            $data->setUpdatedBy(Apollo::getInstance()->getConsole()->getEntity());
            if($field->isMultiple()) {
                $value = [];
                $data->setLongText(serialize($value));
            } elseif($field->hasDefault()) {
                $data->setInt(0);
                $data->setIsDefault(true);
            }
            DB::getEntityManager()->persist($data);
            DB::getEntityManager()->flush();
        }
        return $data;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return PersonEntity
     */
    public function getPerson()
    {
        return $this->person;
    }
    /**
     * @param PersonEntity $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }
    /**
     * @return DataEntity[]
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @param DataEntity[] $data
     */
    public function addData($data)
    {
        $this->data[] = $data;
    }
    /**
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }
    /**
     * @param DateTime $created_on
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }
    /**
     * @return UserEntity
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }
    /**
     * @param UserEntity $created_by
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
    }
    /**
     * @return DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }
    /**
     * @param DateTime $updated_on
     */
    public function setUpdatedOn($updated_on)
    {
        $this->updated_on = $updated_on;
    }
    /**
     * @return UserEntity
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }
    /**
     * @param UserEntity $updated_by
     */
    public function setUpdatedBy($updated_by)
    {
        $this->updated_by = $updated_by;
    }
    /**
     * @return boolean
     */
    public function isHidden()
    {
        return $this->is_hidden;
    }
    /**
     * @param boolean $is_hidden
     */
    public function setIsHidden($is_hidden)
    {
        $this->is_hidden = $is_hidden;
    }
}