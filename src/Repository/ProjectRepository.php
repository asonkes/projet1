<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * Fonction "findProjectPaginated" ==> nom de fonction inventé
     *
     * @param integer $page // Savoir sur quelle page on se trouve
     * @return array // car c'est un tableau d'information que l'on va avoir
     * @return limit $limit // pour savoir la limit des informations que l'on affiche par page
     */
    public function findProjectPaginated(int $page, $user, int $limit = 2): array
    {
        $result = [];

        // Et là se sont les projets en fonction de "User", donc 'p', 'u'
        // $query = requête
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'ASC')
            // On force à avoir une page de minimum 1
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ->getQuery();

        // méthode pour paginer    
        $paginator = new Paginator($query, true);

        // Obtenir les résultats paginés
        $data = iterator_to_array($paginator);

        // On vérifie qu'on a des données
        if (empty($data)) {
            return $result;
        }

        // On calcule le nombre de pages
        // ceil ==> arrondit supérieur
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }
}
