<?php

namespace App\Repository;

use App\Entity\Reseau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reseau>
 *
 * @method Reseau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reseau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reseau[]    findAll()
 * @method Reseau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReseauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reseau::class);
    }

//    /**
//     * @return Reseau[] Returns an array of Reseau objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reseau
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
