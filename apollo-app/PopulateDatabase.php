<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02/03/16
 * Time: 10:38
 */

require_once 'vendor/autoload.php';

use Apollo\Components\DB;
use Apollo\Entities\DataEntity;
use Apollo\Entities\FieldEntity;
use Apollo\Entities\OrganisationEntity;
use Apollo\Entities\PersonEntity;
use Apollo\Entities\RecordEntity;
use Apollo\Entities\UserEntity;
use Faker\Factory;

$entity_manager = DB::getEntityManager();
$organisationRepo = $entity_manager->getRepository('Apollo\\Entities\\OrganisationEntity');
/**
 * @var OrganisationEntity $organisation
 */
$organisation = $organisationRepo->find(1);
$userRepo = $entity_manager->getRepository('Apollo\\Entities\\UserEntity');
/**
 * @var UserEntity $user
 */
$user = $userRepo->find(1);
$date = new DateTime();
$fieldRepo = $entity_manager->getRepository('Apollo\\Entities\\FieldEntity');
/**
 * @var FieldEntity $email
 * @var FieldEntity $phone
 */
$phone = $fieldRepo->find(1);
$email = $fieldRepo->find(2);

for($i = 0; $i < 100; $i++) {

    $faker = Factory::create();
    $person = new PersonEntity();
    $person->setOrganisation($organisation);
    $person->setGivenName($faker->firstName);
    $person->setMiddleName($faker->firstName);
    $person->setLastName($faker->lastName);
    $person->setIsHidden(false);

    $entity_manager->persist($person);
    $entity_manager->flush();

    $record = new RecordEntity();
    $record->setPerson($person);
    $record->setCreatedBy($user);
    $record->setUpdatedBy($user);
    $record->setStartDate($date);
    $record->setEndDate($date);
    $record->setCreatedOn($date);
    $record->setUpdatedOn($date);
    $record->setIsHidden(false);

    $entity_manager->persist($record);
    $entity_manager->flush();

    $phoneData = new DataEntity();
    $phoneData->setRecord($record);
    $phoneData->setField($phone);
    $phoneData->setUpdatedBy($user);
    $phoneData->setUpdatedOn($date);
    $phoneData->setVarchar($faker->phoneNumber);

    $emailData = new DataEntity();
    $emailData->setRecord($record);
    $emailData->setField($email);
    $emailData->setUpdatedBy($user);
    $emailData->setUpdatedOn($date);
    $emailData->setVarchar($faker->email);

    $entity_manager->persist($phoneData);
    $entity_manager->persist($emailData);
    $entity_manager->flush();


}