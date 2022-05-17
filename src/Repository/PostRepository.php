<?php

namespace App\Repository;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function findForHomepage(int $int = null): array
    {
        if (!empty($int)) {
            return  $this->createQueryBuilder('post')
                ->where('post.createdAt <= :now')
                ->andWhere('post.online = true')
                ->orderBy('post.createdAt', 'DESC')
                ->setParameter('now', new  DateTime())
                ->setMaxResults($int)
                ->getQuery()
                ->getResult();
        }
        return  $this->createQueryBuilder('post')
            ->where('post.createdAt <= :now')
            ->andWhere('post.online = true')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('now', new  DateTime())
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }


    public function findForSidebar(?int $int = null): array
    {
        if (!empty($int)) {
            return  $this->createQueryBuilder('post')
                ->where('post.createdAt <= :now')
                ->andWhere('post.online = true')
                ->orderBy('post.createdAt', 'DESC')
                ->setParameter('now', new  DateTime())
                ->setMaxResults($int)
                ->getQuery()
                ->getResult();
        }
        return  $this->createQueryBuilder('post')
            ->where('post.createdAt <= :now')
            ->andWhere('post.online = true')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('now', new  DateTime())
            ->getQuery()
            ->getResult();
    }


    public function findForBlog(): array
    {
        return $this->createQueryBuilder('post')
            ->select('post', 'category')
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.category', 'category')
            ->leftJoin('post.user', 'user')
            ->where('post.online = true')
            ->andwhere('post.createdAt <= :now')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('now', new  DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findForSitemap(): array
    {
        return $this->createQueryBuilder('post')
            ->select('post', 'category')
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.category', 'category')
            ->leftJoin('post.user', 'user')
            ->where('post.online = true')
            ->andwhere('post.createdAt <= :now')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('now', new  DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findForFeed(): array
    {
        return $this->createQueryBuilder('post')
            ->select('post', 'category')
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.category', 'category')
            ->leftJoin('post.user', 'user')
            ->where('post.online = true')
            ->andwhere('post.createdAt <= :now')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('now', new  DateTime())
            ->getQuery()
            ->setMaxResults(20)
            ->getResult();
    }

    public function findForAdmin(?int $userId = null): array
    {
        $query = $this->createQueryBuilder('post');
        $query->select('post')
            ->addSelect('user')
            ->leftJoin('post.user', 'user')
            ->orderBy('post.id', 'DESC');

        if (!empty($userId)) {
            $query->where("post.user = $userId");
        }

        return $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @return array
     */
    public function findLatest(): array
    {
        return  $this->createQueryBuilder('p')
            ->addSelect('c')
            ->addSelect('o')
            ->addSelect('u')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.category', 'o')
            ->leftJoin('p.user', 'u')
            ->orderBy('p.createdAt', 'DESC')
            ->where('p.createdAt <= :now')
            ->andWhere('p.online = true')
            ->setParameter('now', new  DateTime())
            ->getQuery()
            ->setMaxResults(8)
            ->getResult();
    }

    public function findForFooter(): iterable
    {
        return $this->createQueryBuilder('post')
            ->select('post')
            ->where('post.createdAt <= :now')
            ->orderBy('post.createdAt', 'DESC')
            ->andWhere('post.online = true')
            ->setParameter('now', new  DateTime())
            ->setMaxResults(5)
            ->getQuery()
            ->getResult(Query::HYDRATE_OBJECT);
    }

    /**
     * @return array
     */
    public function getFeatures(): array
    {
        return  $this->createQueryBuilder('p')
            ->addSelect('c')
            ->addSelect('o')
            ->addSelect('u')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.category', 'o')
            ->leftJoin('p.user', 'u')
            ->orderBy('p.createdAt', 'DESC')
            ->where('p.online = true')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function findRandom(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('RANDOM()')
            ->where('p.online = true')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPostByTag(?int $id): array
    {
        return $this->findPublishQuery()
            ->join('p.tags', 'tg')
            ->addSelect('t')
            ->where('tg.id = :id')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findPostByCategory(int $id): array
    {
        return $this->findPublishQuery()
            ->join('p.category', 'c')
            ->addSelect('c')
            ->orderBy('post.createdAt', 'DESC')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('c.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function findPublishQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->select('p, t')
            ->where('p.published = true')
            ->orderBy('p.id', 'DESC');
    }

    public function findForArchive(int $int = null): array
    {
        if (!empty($int)) {
            return  $this->createQueryBuilder('post')
                ->where('post.createdAt <= :now')
                ->andWhere('post.online = true')
                ->orderBy('post.createdAt', 'DESC')
                ->setParameter('now', new  DateTime())
                ->setMaxResults($int)
                ->getQuery()
                ->getResult();
        }
        return  $this->createQueryBuilder('post')
            ->where('post.createdAt <= :now')
            ->andWhere('post.online = true')
            ->orderBy('post.createdAt', 'DESC')
            ->setParameter('now', new  DateTime())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $month
     * @param int $year
     * 
     * @return object[]
     */
    public function findByDate($year = null, $month = null)
    {
        if ($month === null) {
            $month = (int) date('m');
        }

        if ($year === null) {
            $year = (int) date('Y');
        }


        $startDate = new \DateTimeImmutable("$year-$month-01T00:00:00");
        $endDate = $startDate->modify('last day of this month')->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('object');
        $qb->where('object.date BETWEEN :start AND :end');
        $qb->setParameter('start', $startDate->format('‌​Y-m-d H:i:s'));
        $qb->setParameter('end', $endDate->format('‌​Y-m-d H:i:s'));

        return $qb->getQuery()->getResult();
    }



    public function findByYear(int $year): ?array
    {
        if (empty($year)) {
            return null;
        }

        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $qb = $this->createQueryBuilder('post');
        $qb->where('post.online = true')
            ->orderBy('post.createdAt', 'DESC')->where('YEAR(post.createdAt) = :year');


        $qb->setParameter('year', $year);
        return $qb->getQuery()->getResult();
    }

    public function findByYearMonth(int $year, int $month): ?array
    {
        if (empty($year) || empty($month)) {
            return null;
        }

        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        $qb = $this->createQueryBuilder('post');
        $qb->where('YEAR(post.createdAt) = :year')
            ->andWhere('MONTH(post.createdAt) = :month');

        $qb->setParameter('year', $year)
            ->setParameter('month', $month);
        return $qb->getQuery()->getResult();
    }
}
