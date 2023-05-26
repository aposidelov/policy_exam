<?php

namespace App\Repository;

use App\Entity\InsurancePolicyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InsurancePolicyType>
 *
 * @method InsurancePolicyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method InsurancePolicyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method InsurancePolicyType[]    findAll()
 * @method InsurancePolicyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InsurancePolicyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InsurancePolicyType::class);
    }

    public function save(InsurancePolicyType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InsurancePolicyType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InsurancePolicyType[] Returns an array of InsurancePolicyType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InsurancePolicyType
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
