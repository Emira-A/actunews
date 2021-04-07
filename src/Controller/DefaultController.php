<?php


namespace App\Controller;



use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     *Page d'Accueil
     *http://localhost:8000/
     */
    public function index()
    {
        // Recuperation des articles de la BDD
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
                            // chemin vers ma classe Post
            ->findAll(); // findAll() récupère tout nos articles de la BDD

        return $this->render('default/index.html.twig', [
            'posts' => $posts
        ]);
        //return new Response('<h1>ACCUEIL</h1>');
        
    }

    /**
     *Page Categorie
     * http://localhost:8000/politique
     * @Route("/{alias}", name="default_category", methods={"GET"})
     *
     * avec la ligne ci-dessus on crée la route sans aller dans routes.yaml
     * toujours ajouter le nom -> name=""
     * toujours ajouter la méthode pour sécurisé l'application
     */

    public function category(Category $category)
    {
        return $this->render('default/category.html.twig', [
            'category' => $category
        ]);
        //return new Response("<h1>CATEGORIE : $alias</h1>");
    }

    /**
     *Page Article
     * http://localhost:8000/politique/un_alias_ici_1.html
     * @Route("/{category}/{alias}_{id}.html", name="default_post", methods={"GET"})
     */

    public function post(Post $post)
    {
        return $this->render('default/post.html.twig', [
            'post' => $post
    ]);
        //return new Response("<h1>ARTICLES : $id - $alias</h1>");
    }

    /**
     *Page de Contact
     *http://localhost:8000/page/contact
     */
    public function contact()
    {
        return $this->render('default/contact.html.twig');
        //return new Response('<h1>CONTACT</h1>');

    }

    /**
     *Page Mentions Légales
     *http://localhost:8000/page/mentions-legales
     */
    public function mentionsLegales()
    {
        return $this->render('default/mentions-legales.html.twig');
        //return new Response('<h1>MENTIONS LEGALES</h1>');

    }

}
