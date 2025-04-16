<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use App\Service\SendMailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/déconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        // Tu crée un formulaire sous le nom de '$form'
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On va chercher l'utilisateur par son email
            // Ici pas lié à une entité (voir doc symfony ==> form)
            // $form->get('nom_du_champ')->getData();
            $email = $form->get('email')->getData();
            // On doit retrouver l'utilisateur lié à l'e-mail, donc on doit aller dans la base de données
            // Donc va dans "$userRepository"
            // findOneBy ==> un email par utilisateur
            $user = $userRepository->findOneByEmail(['email' => $email]);

            // On vérifie si on a un utilisateur
            if ($user) {
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // On génère un lien de réinitialisation du mot de passe
                // fonctionnalitéde "abstractController" qui permet de générer une url
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // On crée les données du mail
                $context = [
                    'url' => $url,
                    'user' => $user
                ];

                // On envoie le mail
                $mail->send(
                    // from
                    'no-reply@e-commerce.fr',
                    // To
                    $user->getEmail(),
                    // Titre
                    'Réinitialisation de mot de passe',
                    // Template (n'existe pas encore)
                    'password_reset',
                    // Contexte
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');

                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'Un problème est survenu.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            // Et ici, ce form tu le crée à ma vue sous le nom de 'requestPassForm' (nom peut être inventé)
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // On vérifie si on a ce token dans la base de données
        $user = $userRepository->findOneByResetToken($token);

        if ($user) {
            // On va créer un formulaire
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès.');

                return $this->redirectToRoute('app_login');
            }

            return $this->render('Security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }

        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}
