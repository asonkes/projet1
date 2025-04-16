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

        // $query = requête
        // 'p' est un alias pour 'projet' 
        $query = $this->createQueryBuilder('p')
            // Ici on filtre les projets par utlisateur ('u')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            // On ordonne les résultat par 'id' et affichage par ordre croissant
            ->orderBy('p.id', 'ASC')
            // On force à avoir une page qui commence à 1
            ->setFirstResult(($page * $limit) - $limit)
            // Combien d'éléments on récupère
            ->setMaxResults($limit)
            // Je transforme mon "builder" en "requête"
            ->getQuery();

        // On va créer un objet "paginator" qui va gérer la pagination
        // $uery = Et il récupère ta requête et compte en fonction des 'setFirstResult' et 'setMaxResult'
        // True = force doctrine à compter aussi les résultats pour savoir combien il y a de pages    
        $paginator = new Paginator($query, true);

        // Obtenir les résultats paginés (on transforme les résultats en un tableau PHP)
        $data = iterator_to_array($paginator);

        // On vérifie qu'on a des données (si pas de résultat, on retourne un tableau vide)
        if (empty($data)) {
            return $result;
        }

        // On calcule le nombre total de pages
        // ceil ==> arrondit supérieur
        // paginator->count() ==> total d'éléments
        // $limit ==> combien on en montre par page 
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau "$result" avec toutes les infos utiles
        $result['data'] = $data; // projets à afficher sur cette page
        $result['pages'] = $pages; // le nombre total de pages
        $result['page'] = $page; // la page actuelle
        $result['limit'] = $limit; // combien de ^p^rojets par page

        return $result; // Tu renvoies tout au controlleur qui lui va passer à la vue (TWIG)
    }
}
