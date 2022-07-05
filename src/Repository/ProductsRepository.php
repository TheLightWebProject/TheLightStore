<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function add(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT p.name, p.price, p.quantity, p.image, b.name, s.name FROM products p INNER JOIN brands b ON p.brand_id = b.id INNER JOIN suppliers s ON p.supplier_id = s.id
    /**
     * @return Products[]
     */
    public function showAllProduct(): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.price, p.quantity, p.image, b.name as brand, s.name as supplier')
            ->innerJoin('p.brand', 'b')
            ->innerJoin('p.supplier', 's')
            ->orderBy('p.createdDate', 'DESC');

        return $query->getQuery()->execute();
    }

    // SELECT * , COUNT(p.id), SUM(od.quantity)
    // FROM order_details od, products p
    // WHERE od.product_id = p.id
    // GROUP BY p.id
    // ORDER BY SUM(od.quantity)
    // DESC
    // LIMIT 4;
    /**
     * @return Products[]
     */
    public function showTop4BestSelling(): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.price, p.image, COUNT(p.id), SUM(od.quantity)')
            ->leftJoin('p.orderDetails', 'od')
            ->groupBy('p.id')
            ->orderBy('SUM(od.quantity)', 'DESC')
            ->addOrderBy('p.createdDate', 'DESC')
            ->setMaxResults(4);
        return $query->getQuery()->execute();
    }

    // SELECT *, COUNT(p.id), SUM(od.quantity)
    // FROM products p LEFT OUTER JOIN order_details od
    // ON p.id = od.product_id
    // GROUP BY p.id
    // ORDER BY p.created_date DESC, SUM(od.quantity) DESC
    /**
     * @return Products[]
     */
    public function showShop(): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.price, p.image, COUNT(p.id), SUM(od.quantity)')
            ->leftJoin('p.orderDetails', 'od')
            ->groupBy('p.id')
            ->orderBy('p.createdDate', 'DESC')
            ->addOrderBy('SUM(od.quantity)', 'DESC');
        return $query->getQuery()->execute();
    }

    /**
     * @return Products[]
     */
    public function noti(): array
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(10);
        return $query->getQuery()->execute();
    }

    //SELECT p.id, p.name, p.price, p.small_desc, p.detail_desc, p.quantity, p.image, b.name, s.name FROM products p INNER JOIN brands b ON p.brand_id = b.id INNER JOIN suppliers s ON p.supplier_id = s.id WHERE p.id = 5
    /**
     * @return Product[]
     */
    public function viewDetail($id): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.price, p.smallDesc, p.detailDesc, p.quantity, p.image, b.name as nameBrand, s.name as nameSup')
            ->innerJoin('p.brand', 'b')
            ->innerJoin('p.supplier', 's')
            ->where('p.id = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->execute();
    }

    /**
     * @return Product[]
     */
    public function findBySearch($value)
    {
        // $query = $this->createQueryBuilder('p')
        //     ->where('p.name LIKE :value')
        //     ->setParameter('value', '%' . $value . '%');
        // return $query->getQuery()->execute();

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM products WHERE ' . implode('AND ', $value) . '';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    //SELECT p.id, p.name, p.price, p.quantity, p.image, b.name as brand, s.name as supplier FROM products p INNER JOIN brands b ON p.brand_id = b.id INNER JOIN suppliers s ON p.supplier_id = s.id WHERE . implode('AND ', $value) . ''
    /**
     * @return Products[]
     */
    public function findBySearchProduct($name)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT p.id, p.name, p.price, p.quantity, p.image, b.name as brand, s.name as supplier FROM products p INNER JOIN brands b ON p.brand_id = b.id INNER JOIN suppliers s ON p.supplier_id = s.id WHERE ' . implode('AND ', $name) . '';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    //    /**
    //     * @return Products[] Returns an array of Products objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Products
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
