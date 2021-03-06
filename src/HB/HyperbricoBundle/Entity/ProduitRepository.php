<?php

namespace HB\HyperbricoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends EntityRepository
{
	public function findProduitsParPageQueryBuilder($page, $id)
	{
		$query = $this->createQueryBuilder('p')
                      ->where('p.catalogue = :id')
              		  ->setParameter('id', $id)
              		  ->andWhere('p.page = :page')
              		  ->setParameter('page', $page);

        return $query;
	}

	public function findProduitsParPage($page, $id)
	{
        return $this->findProduitsParPageQueryBuilder($page, $id)->getQuery()->getResult();
	}
}
