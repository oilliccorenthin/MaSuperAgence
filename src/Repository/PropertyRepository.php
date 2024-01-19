<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Query\QueryException;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    private function findVisibileQuery()
    {
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
    }

    public function findAllVisibleQuery(Filter $filter): ORMQuery
    {
        $query = $this->findVisibileQuery();
        if ($filter->getMaxPrice()){
            $query = $query->andWhere('p.price <= :maxprice')
                ->setParameter('maxprice', $filter->getMaxPrice());
        }
        if ($filter->getMinSurface()){
            $query = $query->andWhere('p.surface >= :minsurface')
                ->setParameter('minsurface', $filter->getMinSurface());
        }
        if ($filter->getOptions()->count() > 0){
            $k = 0;
            foreach($filter->getOptions() as $option){
                $k++;
                $query = $query->andWhere(":option$k MEMBER OF p.options")
                ->setParameter("option$k", $option);
            }
        }

        return $query->getQuery();
    }

    public function findLatest(): array
    {
        return $this->findVisibileQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
//        ;
    }
//    /**
//     * @return Property[] Returns an array of Property objects
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

//    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
