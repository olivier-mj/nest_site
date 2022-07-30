<?php

namespace App\Repository;

use DateTime;
use App\Entity\Event;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function findForHomepage() : array
    {
        $query = $this->createQueryBuilder('event');

        $query->select('event')
        ->andwhere('event.createdAt <= :now')
        ->orderBy('event.createdAt', 'DESC')
        ->setParameter('now', new  DateTime());

        return $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }


    public function findForAdmin() : array
    {
        $query = $this->createQueryBuilder('event');

        $query->select('event')
        ->andwhere('event.createdAt <= :now')
        ->orderBy('event.createdAt', 'DESC')
        ->setParameter('now', new  DateTime());

        return $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }
}
