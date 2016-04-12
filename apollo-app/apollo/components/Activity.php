<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Apollo\Apollo;
use Apollo\Entities\ActivityEntity;


/**
 * Class Activity
 *
 * @package Apollo\Components
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.3
 */
class Activity extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\ActivityEntity';

    /**
     * @return int
     */
    public static function getMinId()
    {
        $em = DB::getEntityManager();
        $repo = $em->getRepository(Activity::getEntityNamespace());
        $qb = $repo->createQueryBuilder('a');
        $organisation_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $notHidden = $qb->expr()->eq('a' . '.is_hidden', '0');
        $sameOrgId = $qb->expr()->eq('a' . '.organisation', $organisation_id);
        $cond = $qb->expr()->andX($notHidden, $sameOrgId);
        $qb->where($cond);
        $query = $qb->getQuery()
        ->setFirstResult(0)
        ->setMaxResults(1);
        $result = $query->getResult();
        $item = $result[0]->getId();
        /*    ->setFirstResult(0)
            ->setMaxResults(1)
            ->getResult();*/
        return $item;
    }

    /**
     * @param $id
     * @return int
     */
    public static function getNumSmallerIds($id)
    {
        $em = DB::getEntityManager();
        $repo = $em->getRepository(Activity::getEntityNamespace());
        $qb = $repo->createQueryBuilder('a');
        $organisation_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $notHidden = $qb->expr()->eq('a' . '.is_hidden', '0');
        $sameOrgId = $qb->expr()->eq('a' . '.organisation', $organisation_id);
        $cond = $qb->expr()->andX($notHidden, $sameOrgId);
        $qb->where($cond);
        $qb->andWhere('a.id < ' . $id);
        $result = $qb->getQuery()->getResult();
        return count($result);
    }

    /**
     * @param $id
     * @return ActivityEntity
     */
    public static function getValidActivity($id)
    {
        $org = Apollo::getInstance()->getUser()->getOrganisationId();
        return self::getRepository()->findBy(['id' => $id, 'is_hidden' => false, 'organisation' => $org])[0];
    }
}