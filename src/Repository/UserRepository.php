<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findUserByRole(?string $roles)
    {

        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :val')
            ->setParameter('val', $roles)
            ->orderBy('u.nomArtiste', 'ASC')
        ;
        return $query->getQuery()->getResult();
    }

    public function findAllUserByDate(?string $roles)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :val')
            ->setParameter('val', $roles)
            ->orderBy('u.dateCreationCompte', 'DESC')
            ->addOrderBy('u.nomArtiste', 'ASC')
            ->setMaxResults(5);
    
        return $query->getQuery()->getResult();
    }

    // findArtisteHome

    // On crée une requète dont le paramètre ('u') est l'alias de la table utilisateur

    // ->where('u.roles LIKE :val'): Cette ligne ajoute une clause WHERE à la requête pour filtrer les utilisateurs dont le champ roles est similaire à une valeur spécifiée. Le LIKE est utilisé pour effectuer une recherche partielle. Le paramètre :val est un paramètre nommé qui sera remplacé par une valeur lors de l'exécution de la requête.

    // ->setParameter('val', $roles): associe valeur au paramètre :val 

    // ->orderBy('u.nomArtiste', 'ASC'): spécifie que les résultats de la requête doivent être triés par ordre croissant en fonction de la colonne nomArtiste de la

    // ->setMaxResults(5) : définit le nombre de résultat

    // return $query->getQuery()->getResult():  exécute la requête en appelant getQuery() pour obtenir un objet Query représentant la requête construite, puis getResult() pour obtenir les résultats de la requête. Les résultats sont renvoyés par la méthode.


//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
