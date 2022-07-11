<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function add(Orders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Orders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT * FROM orders o INNER JOIN customers c ON o.username_id = c.id ORDER BY o.order_date DESC;
    /**
     * @return Orders[]
     */
    public function showAllOrder(): array
    {
        $query = $this->createQueryBuilder('o')
            ->select('o.id, o.orderDate, o.deliveryDate, o.checked, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
            ->innerJoin('o.username', 'c')
            ->orderBy('o.orderDate', 'DESC');
        return $query->getQuery()->execute();
    }

    //SELECT * FROM orders o WHERE MONTH(o.order_date) = 7
    /**
     * @return Orders[]
     */
    public function findDateOfOrder($year, $month, $day): array
    {
        $query = [];
        if ($day == 0 && $month == 0) {
            $query = $this->createQueryBuilder('o')
                ->select('o.id, o.orderDate, o.deliveryDate, o.checked, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
                ->where('YEAR(o.orderDate) = :year')
                ->setParameter('year', $year)
                ->orderBy('o.orderDate', 'DESC');
        } elseif ($day == 0) {
            $query = $this->createQueryBuilder('o')
                ->select('o.id, o.orderDate, o.deliveryDate, o.checked, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
                ->where('YEAR(o.orderDate) = :year')
                ->setParameter('year', $year)
                ->andWhere('MONTH(o.orderDate) = :month')
                ->setParameter('month', $month)
                ->orderBy('o.orderDate', 'DESC');
        } else {
            $query = $this->createQueryBuilder('o')
                ->select('o.id, o.orderDate, o.deliveryDate, o.checked, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
                ->where('YEAR(o.orderDate) = :year')
                ->setParameter('year', $year)
                ->andWhere('MONTH(o.orderDate) = :month')
                ->setParameter('month', $month)
                ->andWhere('DAY(o.orderDate) = :day')
                ->setParameter('day', $day)
                ->orderBy('o.orderDate', 'DESC');
        }
        return $query->getQuery()->execute();
    }

    // /**
    //  * @return Orders[]
    //  */
    // public function findByMonthAndYear($year, $month): array
    // {
    //     $query = $this->createQueryBuilder('o')
    //         ->select('o.id, o.orderDate, o.deliveryDate, o.checked, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
    //         ->where('YEAR(o.orderDate) = :year')
    //         ->setParameter('year', $year)
    //         ->andWhere('MONTH(o.orderDate) = :month')
    //         ->setParameter('month', $month)
    //         ->orderBy('o.orderDate', 'DESC');
    //     return $query->getQuery()->execute();
    // }

    // /**
    //  * @return Orders[]
    //  */
    // public function findByYear($year): array
    // {
    //     $query = $this->createQueryBuilder('o')
    //         ->select('o.id, o.orderDate, o.deliveryDate, o.checked, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
    //         ->where('YEAR(o.orderDate) = :year')
    //         ->setParameter('year', $year)
    //         ->orderBy('o.orderDate', 'DESC');
    //     return $query->getQuery()->execute();
    // }

    //    /**
    //     * @return Orders[] Returns an array of Orders objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Orders
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
