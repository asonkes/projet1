<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProjectsVoter extends Voter
{
    // Ici,on déclare la constante "edit" => alias lisible pour éviter de réécrire 'PROJECT_EDIT' partout
    const EDIT = 'PROJECT_EDIT';

    // Ici on déclare la constante "delete" => alias lisible pour éviter de réécrire 'PROJECT_DELETE' partout
    const DELETE = 'PROJECT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // booléen pk => on se poser la question , est ce que telle ou telle personne a le droit de faire ça??? true ou false
    // On a met dans un tableau un retour (de edit ou delete) ==> si on a pas ça ==> false (une autre personne que admin ne pourra pas se connecter)
    protected function supports(string $attribute, $project): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }
        if (!$project instanceof Project) {
            return false;
        }

        return true;
    }

    // booléen pk => on se poser la question , est ce que telle ou telle personne a le droit de faire ça??? true ou false
    // 
    protected function voteOnAttribute($attribute, $project, TokenInterface $token): bool
    {
        // On récupère l'utilisateur à partir du token
        $user = $token->getUser();

        // On vérifie si l'utilisateur est une instance de "userInterface"
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si l'utilisateur ets un "admin"
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Si l'utilisateur est connecté mais n'est pas "admin", on vérifie ses permissions
        switch ($attribute) {
            case self::EDIT:
                // On vérifie si l'utilisateur peut éditer 
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie si l'utilisateur peut supprimer
                return $this->canDelete();
                break;
        }

        return false;
    }

    private function canEdit()
    {
        return $this->security->isGranted('ROLE_PROFILE_MANAGER');
    }

    private function canDelete()
    {
        return $this->security->isGranted('ROLE_PROFILE_MANAGER');
    }
}
