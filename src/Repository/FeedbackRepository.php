<?php

namespace App\Repository;

use App\Entity\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feedback>
 *
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function add(Feedback $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Feedback $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT f.content, f.send_date, c.fullname, p.name, f.allow FROM feedback f INNER JOIN customers c ON f.customer_id = c.id INNER JOIN products p ON f.product_id = p.id ORDER BY f.send_date DESC
    /**
     * @return Feedback[]
     */
    public function showFeedback(): array
    {
        $query = $this->createQueryBuilder('f')
            ->select('f.id as feedID, f.content, f.sendDate, c.fullname, p.id as proID, p.name, f.allow, p.image')
            ->innerJoin('f.customer', 'c')
            ->innerJoin('f.product', 'p')
            ->orderBy('f.sendDate', 'DESC');
        return $query->getQuery()->execute();
    }

    //SELECT c.fullname, f.content FROM feedback f INNER JOIN customers c ON f.customer_id = c.id WHERE f.allow = 0 AND f.product_id = 8
    /**
     * @return Feedback[]
     */
    public function allowDisplayFeedback($id): array
    {
        $query = $this->createQueryBuilder('f')
            ->select('c.fullname, f.content, f.allow')
            ->innerJoin('f.customer', 'c')
            ->Where('f.product = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->execute();
    }

    // /**
    //  * @return Feedback[]
    //  */
    // public function giveFeedback(): array
    // {
    //     $query = $this->createQueryBuilder('f')
    //     ->innerJoin('f.');
    // }

    //    /**
    //     * @return Feedback[] Returns an array of Feedback objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Feedback
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
