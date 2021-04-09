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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/dashboard/post")
 */

class PostController extends AbstractController
{
    /**
     * Page permettant de créer un article.
     * http://localhost:8000/dashboard/post/create
     * @Route("/create", name="post_create", methods={"GET|POST"})
     *
     */
    public function create(Request $request, SluggerInterface $slugger)
    {
        #création d'un nouvel article vierge
        $post = new Post();

        #dump($post);

        # Récupération d'un User dans la BDD
        # Remplacer par le User Connecté
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

        # /!\ Traitement du formulaire par symfony
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){

            # Upload de l'image
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            # récupère les données de notre image de notre formulaire (elle seronts stocké dans notre variable image)

            #si on a bien quelque chose uploadé
            if ($image) {
                #on récupère le nom du fichier
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                #on crée un nom sécurisé -> un slug
                $safeFilename = $slugger->slug($originalFilename);
                #generer un id unique ce qui donne un nouveau nom de fichier
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // stocker nos images - on modifie le directory avec images
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    # TODO Traitement en cas d'erreur de l'upload
                }
                #On sauvegarde dans la BDD le nom du nouveau fichier
                $post->setImage($newFilename);
            }

            # Génération de l'alias
            $post->setAlias(
                $slugger->slug(
                    $post->getTitle()
                )
            );

            # Sauvegarde dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            # Notification de confirmation
            $this->addFlash('success', "Félicitations, votre article est en ligne.");



            # Redirection vers le nouvel article (je prends en compte les paramètres ici category, alias et id
            return $this->redirectToRoute('default_post', [
                'category' => $post->getCategory()->getAlias(),
                'alias' => $post->getAlias(),
                'id' => $post->getId()
            ]);
        }

        # Transmission du formulaire à la vue
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

}
