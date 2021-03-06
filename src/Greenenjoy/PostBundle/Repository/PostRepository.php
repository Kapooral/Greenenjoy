<?php

namespace Greenenjoy\PostBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
	public function getPublished()
	{
		$queryBuilder = $this->createQueryBuilder('p');
		$queryBuilder->where('p.state = :state')->setParameter('state', 'posted')->orderBy('p.postDate', 'DESC');
		$posts = $queryBuilder->getQuery()->getArrayResult();

		return $posts;
	}
}
