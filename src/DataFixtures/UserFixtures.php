<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) 
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d’un utilisateur de type “contributeur” (= auteur)
        $contributor = new User();
        $contributor->setEmail('contributor@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor,
            'contributorpassword'
        );

        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);

        // Création d’un autre utilisateur de type “contributeur” (= auteur)
        $contributor = new User();
        $contributor->setEmail('tatitabo@outlook.fr');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor,
            'tototo'
        );

        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);

        $this->addReference('contributor', $contributor);


        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('adrien.cremeaux@outlook.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'tototo'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        // Création d’un utilisateur
        $user = new User();
        $user->setEmail('fernieadri@gmail.com');
        $admin->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'tototo'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}
