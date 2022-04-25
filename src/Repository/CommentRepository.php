<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findForSidebar(?int $int = null): array
    {

        $qb = $this->createQueryBuilder('comment')
            ->select('comment', 'user', 'post')
            ->leftJoin('comment.user', 'user')
            ->leftJoin('comment.post', 'post')
            ->orderBy('comment.createdAt', 'DESC')
            ->where('post.online = true');

        if (!empty($int)) {
            $qb->setMaxResults($int);
        } else {
            $qb->setMaxResults(5);
        }
        return $qb->getQuery()
            ->getResult();
    }
}
