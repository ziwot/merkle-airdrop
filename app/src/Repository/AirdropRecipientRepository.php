<?php

namespace App\Repository;

use App\Entity\AirdropRecipient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AirdropRecipient>
 *
 * @method AirdropRecipient|null find($id, $lockMode = null, $lockVersion = null)
 * @method AirdropRecipient|null findOneBy(array $criteria, array $orderBy = null)
 * @method AirdropRecipient[]    findAll()
 * @method AirdropRecipient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AirdropRecipientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AirdropRecipient::class);
    }

//    /**
//     * @return AirdropRecipient[] Returns an array of AirdropRecipient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AirdropRecipient
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
