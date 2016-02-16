<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Entities;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;


/**
 * Class OrganisationEntity
 *
 * @package Apollo\Entities
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 * @Entity @Table(name="organisations")
 */
class OrganisationEntity
{
    /**
     * Unique organisation ID
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * Name of the organisation
     * @var string
     * @Column(type="string")
     */
    protected $name;

    /**
     * Organisation default timezone
     * @var string
     * @Column(type="string")
     */
    protected $timezone;

    public function __construct()
    {
        $this->is_admin = false;
    }

}