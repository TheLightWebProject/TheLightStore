<?php

namespace App\Repository;

use App\Entity\Customers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customers>
 *
 * @method Customers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customers[]    findAll()
 * @method Customers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customers::class);
    }

    public function add(Customers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT * FROM customers c INNER JOIN user u ON c.user_id = u.id WHERE u.email = 'quangndgcc200030@fpt.edu.vn'
    /**
     * @return Customers[]
     */
    public function findCustomer($id): array
    {
        $query = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->where('c.user = :id')
            ->setParameter('email', $id);
        return $query->getQuery()->execute();
    }

    //SELECT * FROM customers c INNER JOIN user u ON c.user_id = u.id INNER JOIN orders o ON c.id = o.username_id WHERE u.email = 'quangndgcc200030@fpt.edu.vn' ORDER BY o.order_date DESC
    /**
     * @return Customer[]
     */
    public function showProductOrdered($email): array
    {
        $query = $this->createQueryBuilder('c')
            ->select('o.id, o.orderDate, o.deliveryDate, o.deliveryLocal, o.custName, o.custPhone, o.totalPrice')
            ->innerJoin('c.user', 'u')
            ->innerJoin('c.orders', 'o')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->orderBy('o.orderDate', 'DESC');
        return $query->getQuery()->execute();
    }

    //SELECT * FROM orders o INNER JOIN order_details od ON o.id = od.orders_id INNER JOIN products p ON od.product_id = p.id WHERE od.orders_id = 32
    /**
     * @return Customer[]
     */
    public function showProductOrderedDetail($odID): array
    {
        $query = $this->createQueryBuilder('o')
            ->select()
            ->innerJoin('o.orderDetails', 'od')
            ->innerJoin('od.product', 'p')
            ->where('od.orders = :odID')
            ->setParameter('odID', $odID);
        return $query->getQuery()->execute();
    }

    //SELECT * FROM order_details od, products p WHERE od.product_id = p.id AND od.orders_id = 17
    /**
     * @return Customer[]
     */
    public function findProductOrderDetail($id): array
    {
        $query = $this->createQueryBuilder('od')
            ->innerJoin('od.product', 'p')
            ->where('od.product = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->execute();
    }

    //    /**
    //     * @return Customers[] Returns an array of Customers objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Customers
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
