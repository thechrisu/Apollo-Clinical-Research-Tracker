<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Apollo\Apollo;


/**
 * Class Activity
 *
 * @package Apollo\Components
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.2
 */
class Activity extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\ActivityEntity';

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

}