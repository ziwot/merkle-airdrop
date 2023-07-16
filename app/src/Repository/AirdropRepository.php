<?php

namespace App\Repository;

use App\Entity\Airdrop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Airdrop>
 *
 * @method Airdrop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Airdrop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Airdrop[]    findAll()
 * @method Airdrop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AirdropRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Airdrop::class);
    }

//    /**
//     * @return Airdrop[] Returns an array of Airdrop objects
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

//    public function findOneBySomeField($value): ?Airdrop
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
