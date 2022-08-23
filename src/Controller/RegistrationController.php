<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'user.registration')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_USER')")]
    #[Route('/liste', name: 'user.list')]
    public function index(UserRepository $repository): Response
    {
        return $this->render('liste/index.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }

    #[Security("is_granted('ROLE_USER')")]
    #[Route('/suppression/{id}', name: 'user.delete', methods: 'GET')]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {
        if (!$user) {
            $this->addFlash(
                'warning',
                'Utilisateur n\'a pas été trouvé'
            );
            return $this->redirectToRoute('user.list');
        } else {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(
                'succes',
                'Votre Utilisateur à été supprimer avec succes !'
            );
            return $this->redirectToRoute('user.list');
        }
    }
    #[Security("is_granted('ROLE_USER')")]
    #[Route('/modification/{id}', name:'user.update', methods:['GET','POST'])]
    public function update(EntityManagerInterface $manager, User $user, Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'succes',
                'Votre Utilisateur à été modifier avec succes !'
            );
            return $this->redirectToRoute('user.list');
        }

        return $this->render('registration/update.html.twig', [
            'updateForm' => $form->createView(),
        ]);
    }
}
