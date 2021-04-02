<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{

    /**
     *Page de Contact
     *http://localhost:8000/register
     * @Route("/register", name="user_register", methods={"GET"})
     */
    public function register()
    {
        return new Response('<h1>INSCRIPTION</h1>');
    }
}