<?php

namespace App\Repository;

use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderDetails>
 *
 * @method OrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetails[]    findAll()
 * @method OrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetails::class);
    }

    public function add(OrderDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OrderDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT * FROM order_details od INNER JOIN products p ON od.product_id = p.id WHERE od.orders_id = 13
    /**
     * @return OrderDetails[]
     */
    public function showAllOrderDetail($id): array
    {
        $query = $this->createQueryBuilder('od')
            ->select('od.id as odID, od.quantity, od.totalPrice, p.id as proID, p.name, p.price, p.image')
            ->innerJoin('od.product', 'p')
            ->where('od.orders = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->execute();
    }

    //SELECT * FROM order_details od INNER JOIN products p ON od.product_id = p.id WHERE od.orders_id = 32
    /**
     * @return Orders[]
     */
    public function showProductOrderedDetail($odID): array
    {
        $query = $this->createQueryBuilder('od')
            ->select('od.id as odID, od.quantity, od.totalPrice, p.id as proID, p.name, p.price, p.image')
            ->innerJoin('od.product', 'p')
            ->where('od.orders = :odID')
            ->setParameter('odID', $odID);
        return $query->getQuery()->execute();
    }

    //    /**
    //     * @return OrderDetails[] Returns an array of OrderDetails objects
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

    //    public function findOneBySomeField($value): ?OrderDetails
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
