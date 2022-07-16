<?php

namespace App\Repository;

use App\Entity\CartDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartDetails>
 *
 * @method CartDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartDetails[]    findAll()
 * @method CartDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartDetails::class);
    }

    public function add(CartDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CartDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT * FROM customers cus
    // INNER JOIN cart c ON cus.id = c.customer_id
    // INNER JOIN cart_details cd ON c.id = cd.cart_id
    // INNER JOIN products p ON cd.products_id = p.id WHERE cus.id = 16
    /**
     * @return CartDetails[]
     */
    public function showCartDetails($id): array
    {
        $query = $this->createQueryBuilder('cd')
            ->select('cd.id, p.id as proID, p.name, p.price, p.smallDesc, p.image, cd.quantity, cd.totalPrice')
            ->innerJoin('cd.products', 'p')
            ->innerJoin('cd.cart', 'c')
            ->innerJoin('c.customer', 'cus')
            ->where('cus.id = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->execute();
    }

    //    /**
    //     * @return CartDetails[] Returns an array of CartDetails objects
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

    //    public function findOneBySomeField($value): ?CartDetails
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
