<?php

namespace App\Repository;

use App\Entity\Brands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brands>
 *
 * @method Brands|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brands|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brands[]    findAll()
 * @method Brands[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brands::class);
    }

    public function add(Brands $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Brands $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Brands[]
     */
    public function findBySearchBrand($value): array
    {
        $query = $this->createQueryBuilder('b')
            ->where('b.name LIKE :value')
            ->setParameter('value', '%' . $value . '%');
        return $query->getQuery()->execute();
    }

    // SELECT b.id, b.image, b.name, COUNT(b.id), SUM(od.quantity)
    // FROM brands b INNER JOIN products p
    // ON b.id = p.brand_id INNER JOIN order_details od
    // ON P.id = od.product_id
    // GROUP BY b.id
    // ORDER BY SUM(od.quantity)
    // DESC
    /**
     * @return Brands[]
     */
    public function showBrandHome(): array
    {
        $query = $this->createQueryBuilder('b')
            ->select('b.id, b.image, b.name, COUNT(b.id), SUM(od.quantity)')
            ->innerJoin('b.products', 'p')
            ->innerJoin('p.orderDetails', 'od')
            ->groupBy('b.id')
            ->orderBy('SUM(od.quantity)', 'DESC')
            ->setMaxResults(4);
        return $query->getQuery()->execute();
    }

    //    /**
    //     * @return Brands[] Returns an array of Brands objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Brands
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
