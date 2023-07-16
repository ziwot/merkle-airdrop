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

  public function recentAirdrops()
  {
    return $this->createQueryBuilder('a')
      ->select('COUNT(r.id) AS nb_recipients, a.name, a.description')
      ->join('a.airdropRecipients', 'r')
      ->orderBy('a.id', 'DESC')
      ->groupBy('a.id')
      ->setMaxResults(5)
      ->getQuery()
      ->getResult();
  }
}
