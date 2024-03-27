<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Album>
 *
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

//    /**
//     * @return Album[] Returns an array of Album objects
//     */
//    public function findByExampleField(?string ): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(3)

//            return $query->getQuery()->getResult();
//        ;
//    }

   /**
    * Retourne le nombre d'album
    * @return void 
    */
   public function findAlbums($filters = null): array
   {
       $query = $this->createQueryBuilder('a')
            ->Where('a.ban = false');


        if($filters != null){

            $query->andWhere('a.genreMusicals IN(:genres)')
            ->setParameter(':genres', array_values($filters));
        }

        $query->orderBy('a.id', 'ASC');
        ;

        return $query->getQuery()->getResult();
   }



//    public function findOneBySomeField($value): ?Album
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
