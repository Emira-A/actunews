<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
        // fonction Load va charger les fausses données. Elle prend la Classe ObjectManager, elle sait aller sauvegarder nos entitées
    {

        // Préparation de Faker
        $faker = Factory::create('fr_FR');

        // ---------- | LES CATEGORIES
        $politique = new Category();
        $politique->SetName('Politique');
        $politique->SetAlias('politique');
        $manager->persist($politique);

        $economie = new Category();
        $economie->SetName('Economie');
        $economie->SetAlias('economie');
        $manager->persist($economie);

        $sante = new Category();
        $sante->SetName('Santé');
        $sante->SetAlias('sante');
        $manager->persist($sante);

        $culture = new Category();
        $culture->SetName('Culture');
        $culture->SetAlias('culture');
        $manager->persist($culture);

        $sport = new Category();
        $sport->SetName('Sport');
        $sport->SetAlias('sport');
        $manager->persist($sport);

        // On sauvegarde le tout dans la BDD
        $manager->flush();

        // ---------- | LES UTILISATEURS

        // Création d'un admin
        $admin = new User();
        $admin->setFirstname('Emira');
        $admin->setLastname('AMAMI');
        $admin->setEmail('emira@actu.news');
        $admin->setPassword('test');
        $admin->setRoles(['ROLE_USER']);
        $manager->persist($admin);

        // Création d'utilisateurs normaux
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword('test');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        // On sauvegarde le tout dans la BDD
        $manager->flush();

        // ---------- | LES ARTICLES
        for ($i = 0; $i < 5; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6));
            $post->setContent($faker->text);
            $post->setImage($faker->imageUrl(500,350));
            $post->setAlias('lorem-ipsum-dolor-este');
            $post->setCategory($politique);
            $post->setUser($admin);
            $manager->persist($post);

            $post2 = new Post();
            $post2->setTitle($faker->sentence());
            $post2->setContent($faker->text);
            $post2->setImage($faker->imageUrl(500,350));
            $post2->setAlias('lorem-ipsum-dolor-este');
            $post2->setCategory($culture);
            $post2->setUser($admin);
            $manager->persist($post2);

            $post3 = new Post();
            $post3->setTitle($faker->sentence());
            $post3->setContent($faker->text);
            $post3->setImage($faker->imageUrl(500,350));
            $post3->setAlias('lorem-ipsum-dolor-este');
            $post3->setCategory($economie);
            $post3->setUser($admin);
            $manager->persist($post3);
        }

        // On sauvegarde le tout dans la BDD
        $manager->flush();

    }
}
