<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     *Page de Contact
     *http://localhost:8000/register
     * @Route("/user/register", name="user_register", methods={"GET|POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder )
    {
        # Création d'un nouvel utilisateur
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        # Création du formulaire
        $form = $this->createFormBuilder($user)

            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class)

            ->add('password', PasswordType::class, [
                'label' => 'Mot de Passe'
            ])
            ->add( 'submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->getForm();

        # Traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            # Encodage du mdp
            $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            # Sauvegarde dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # TODO Notification flash
            $this->addFlash('success',
            "Merci pour votre inscription. Vous pouvez vous connecter.");

            # TODO Redirection
            return $this->redirectToRoute('index');
        }

        # Affichage du formulaire
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
