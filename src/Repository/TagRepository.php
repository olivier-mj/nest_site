<?php

namespace App\Repository;

use App\Entity\Tag;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry,)
    {
        parent::__construct($registry, Tag::class);

    }

    public function findForFooter(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function findForSidebar(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function findForBlog(): array
    {
        return $this->createQueryBuilder('tag')
            ->orderBy('tag.name', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function search(string $tagName): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.name LIKE :tag')
            ->setParameter('tag', "%$tagName%")
            ->setMaxResults(15)
            ->getQuery()
            ->getResult();
    }


//    public function findTag(string $tag): array
//    {
//        return $this->createQueryBuilder('tag');
//
//    }

}
