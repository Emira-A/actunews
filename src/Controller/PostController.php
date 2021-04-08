<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/post")
 */

class PostController extends AbstractController
{
    /**
     * Page permettant de créer un article.
     * @Route("/create", name="post_create", methods={"GET|POST"})
     *
     */
    public function create()
    {
        #création d'un nouvel article vierge
        $post = new Post();

        # Récupération d'un User dans la BDD
        # TODO à remplacer par le User Connecté
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByEmail('emira@actu.news');

        # On affecte le journaliste (user) à l'article
        $post->setUser($user);

        # Création du Formulaire
        $form = $this->createFormBuilder($post)

            #Titre de l'article
            ->add('title', TextType::class, [
                'label' => "Titre de mon article",
                'attr' => [
                    'placeholder' => "Titre de mon article"
                ]
            ])

            #Category
                ->add('category',EntityType::class,[
                // looks for choices from this entity
                'label' => "Choisissez une catégorie",
                'class' => Category::class,
                'choice_label' => 'name',
            ])

            # Content
                ->add('content', TextareaType::class, [
                    'label' => false
                    # le false permet d'enlever la mention 'content' au dessus de mon bloc
            ])

            # Image
                ->add('image', FileType::class, [
                    'label' => 'Illustration'
            ])

            #Submit
                ->add( 'submit', SubmitType::class, [
                    'label' => "Enregistrer les modifications"
             ])

        #Fialiser norte formulaire
        ->getForm();

        # Transmission du formulaire à la vue
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

}
