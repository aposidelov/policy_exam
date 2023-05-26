<?php

namespace App\Repository;

use App\Entity\UserPolicy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPolicy>
 *
 * @method UserPolicy|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPolicy|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPolicy[]    findAll()
 * @method UserPolicy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPolicyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPolicy::class);
    }

    public function save(UserPolicy $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserPolicy $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function loadEntity($uid, $pid): array
    {
        return $this->createQueryBuilder('up')
            ->andWhere('up.uid = :uid AND up.pid = :pid')
            ->setParameter('uid', $uid)
            ->setParameter('pid', $pid)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return UserPolicy[] Returns an array of UserPolicy objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserPolicy
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
