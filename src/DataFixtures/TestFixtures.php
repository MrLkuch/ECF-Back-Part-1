<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Emprunteur;
use App\Entity\Emprunt;



use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class TestFixtures extends Fixture
{
    private $doctrine;
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;

    }

public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadUser();
        $this->loadAuteur();
        $this->loadLivre();
        $this->loadEmprunteur();
        $this->loadEmprunt();


    }

    public function loadUser(): void
    {
        // données de test statiques
        $datas = [
            [
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true,
                'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 09:00:00'),
                'updated_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 09:00:00'),

            ],
            [
                'email' => 'foo.foo@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true,
                'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
                'updated_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),


            ],
            [
                'email' => 'bar.bar@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => false,
                'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 11:00:00'),
                'updated_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00'),

            ],
            [
                'email' => 'baz.baz@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true,
                'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                'updated_at' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),

            ]
        ];


        foreach ($datas as $data) {
            // création d'un nouvel objet
            $user = new User();
            // affectation des valeurs statiques
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($data['password']);
            $user->setEnabled($data['enabled']);
            $user->setCreatedAt($data['created_at']);
            $user->setUpdatedAt($data['updated_at']);



            // demande d'enregistrement de l'objet
            $this->manager->persist($user);
        };


        for ($i = 0; $i < 100; $i++) {
            $this->faker->email();
            $this->faker->word();
            $this->faker->boolean();
            $this->faker->dateTimeBetween();

            $user = new User();

            // affectation des valeurs dynamiques

            $user->setEmail($this->faker->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->faker->word());
            $user->setEnabled($this->faker->boolean());
            $user->setCreatedAt($this->faker->dateTimeBetween('-10 week', '-6 week'));
            $user->setUpdatedAt(($this->faker->dateTimeBetween('+8 week', '+12 week')));

            $this->manager->persist($user);
        }

        $this->manager->flush();
    }


    public function loadLivre(): void
    {
        // données de test statiques

        $repository = $this->manager->getRepository(Auteur::class);
        $auteurs = $repository->findAll();

        $repository = $this->manager->getRepository(Emprunt::class);
        $emprunts = $repository->findAll();

        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'annee_edition' => 2010,
                'nombre_pages' => 100,
                'code_isbn' => '9785786930024',
                'auteur_id' => $auteurs[0],

            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'annee_edition' => 2011,
                'nombre_pages' => 150,
                'code_isbn' => '9783817260935',
                'auteur_id' => $auteurs[1],


            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'annee_edition' => 2012,
                'nombre_pages' => 200,
                'code_isbn' => '9782020493727',
                'auteur_id' => $auteurs[2],

            ],
            [
                'titre' => 'Quem audis satis belle',
                'annee_edition' => 2013,
                'nombre_pages' => 250,
                'code_isbn' => '9794059561353',
                'auteur_id' => $auteurs[3],

            ]
        ];


        foreach ($datas as $data) {
            // création d'un nouvel objet
            $livre = new Livre();
            // affectation des valeurs statiques
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['annee_edition']);
            $livre->setNombrePages($data['nombre_pages']);
            $livre->setCodeIsbn($data['code_isbn']);
            $livre->setAuteur($data['auteur_id']);

         



            // demande d'enregistrement de l'objet
            $this->manager->persist($livre);

            
            
        };


        for ($i = 0; $i < 100; $i++) {
            $this->faker->word();
            $this->faker->year();
            $this->faker->randomNumber();
            $this->faker->numerify();

            $livre = new Livre();

            // affectation des valeurs dynamiques

            $livre->setTitre(ucfirst($this->faker->word()));
            $livre->setAnneeEdition($this->faker->year());
            $livre->setNombrePages($this->faker->randomNumber(4, false));
            $livre->setCodeIsbn($this->faker->numerify('#############'));

            $this->manager->persist($livre);
        }

        $this->manager->flush();
    }


    public function loadAuteur(): void
    {
        // données de test statiques
        $datas = [
            [
                'nom' => 'auteur inconnu',
                'prenom' => null,

            ],
            [
                'nom' => 'Cartier',
                'prenom' => 'Hugues',

            ],
            [
                'nom' => 'Lambert',
                'prenom' => 'Armand',
            ],
            [
                'nom' => 'Moitessier',
                'prenom' => 'Thomas',
            ]
        ];


        foreach ($datas as $data) {
            // création d'un nouvel objet
            $auteur = new Auteur();
            // affectation des valeurs statiques
            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);
         



            // demande d'enregistrement de l'objet
            $this->manager->persist($auteur);
        };


        for ($i = 0; $i < 500; $i++) {
            $this->faker->word();

            $auteur = new Auteur();

            // affectation des valeurs dynamiques

            $auteur->setNom(ucfirst($this->faker->word()));
            $auteur->setPrenom($this->faker->word());

            $this->manager->persist($auteur);
        }

        $this->manager->flush();
    }

    public function loadEmprunteur(): void
    {

        $repository = $this->manager->getRepository(User::class);
        $users = $repository->findAll();
        // données de test statiques
        $datas = [
            [
                'nom' => 'foo',
                'prenom' => 'foo',
                'tel' => '123456789',
                'user_id' => $users[1],

            ],
            [
                'nom' => 'bar',
                'prenom' => 'bar',
                'tel' => '123456789',
                'user_id' => $users[2],


            ],
            [
                'nom' => 'baz',
                'prenom' => 'baz',
                'tel' => '123456789',
                'user_id' => $users[3],

            ],

        ];


        foreach ($datas as $data) {
            // création d'un nouvel objet
            $emprunteur = new Emprunteur();
            // affectation des valeurs statiques
            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTel($data['tel']);
            $emprunteur->setUser($data['user_id']);

         



            // demande d'enregistrement de l'objet
            $this->manager->persist($emprunteur);
        };



        $this->manager->flush();
    }

    public function loadEmprunt(): void
    {

        $repository = $this->manager->getRepository(Emprunteur::class);
        $emprunteurs = $repository->findAll();

        $repository = $this->manager->getRepository(Livre::class);
        $livres = $repository->findAll();
        // données de test statiques
        $datas = [
            [
                'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'),
                'date_retour' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
                'emprunteur_id' => $emprunteurs[0],
                'livre_id' => $livres[0],
            ],
            [
                'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
                'date_retour' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'),
                'emprunteur_id' => $emprunteurs[1],
                'livre_id' => $livres[1],

            ],
            [
                'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-0-01 10:00:00'),
                'date_retour' => null,
                'emprunteur_id' => $emprunteurs[2],
                'livre_id' => $livres[2],
            ],

        ];


        foreach ($datas as $data) {
            // création d'un nouvel objet
            $emprunt = new Emprunt();
            // affectation des valeurs statiques
            $emprunt->setDateEmprunt($data['date_emprunt']);
            $emprunt->setDateRetour($data['date_retour']);
            $emprunt->setEmprunteur($data['emprunteur_id']);
            $emprunt->setLivre($data['livre_id']);
            



            // demande d'enregistrement de l'objet
            $this->manager->persist($emprunt);
        };



        $this->manager->flush();
    }
}

