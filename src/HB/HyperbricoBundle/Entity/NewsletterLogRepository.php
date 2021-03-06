<?php

namespace HB\HyperbricoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NewsletterLogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsletterLogRepository extends EntityRepository
{
	public function findNewsletterLog($id, $destinataire)
    {
        $query = $this->createQueryBuilder('n')
                      ->where('n.newsletter = :id')
                      ->setParameter('id', $id)
                      ->andWhere('n.destinataire = :destinataire')
                      ->setParameter('destinataire', $destinataire);

        return $query->getQuery()->getResult();
    }
}
