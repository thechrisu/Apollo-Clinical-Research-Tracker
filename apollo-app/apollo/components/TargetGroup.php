<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Apollo\Apollo;


/**
 * Class TargetGroup
 *
 * @package Apollo\Components
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.21
 */
class TargetGroup extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\TargetGroupEntity';

    public static function getMin()
    {
        $em = DB::getEntityManager();
        $repo = $em->getRepository(TargetGroup::getEntityNamespace());
        $qb = $repo->createQueryBuilder('t');
        $organisation_id = Apollo::getInstance()->getUser()->getOrganisationId();
        $notHidden = $qb->expr()->eq('t' . '.is_hidden', '0');
        $sameOrgId = $qb->expr()->eq('t' . '.organisation', $organisation_id);
        $cond = $qb->expr()->andX($notHidden, $sameOrgId);
        $qb->where($cond);
        $query = $qb->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1);
        $result = $query->getResult();
        $item = $result[0];
        return $item;
    }
}