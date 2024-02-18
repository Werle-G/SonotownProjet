<?php

namespace App\Repository;

use App\Entity\GenreMusical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GenreMusical>
 *
 * @method GenreMusical|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenreMusical|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenreMusical[]    findAll()
 * @method GenreMusical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreMusicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenreMusical::class);
    }

//    /**
//     * @return GenreMusical[] Returns an array of GenreMusical objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GenreMusical
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
