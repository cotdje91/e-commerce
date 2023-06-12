<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('admin@test.fr');
        $admin->setLastname('Kabouet');
        $admin->setFirstname('Jason');
        $admin->setAddress('10 Rue Des Cheminots');
        $admin->setZipcode('91200');
        $admin->setCity('Athis-Mons');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'jeje91160')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // On utilie FAKER pour avoir des donnéees en francais
        $faker= Factory::create('fr_FR');

        //On boucle pour créer plusieur utilisateurs
        for($urs = 1; $urs <=5 ; $urs++){
            $user = new Users();
            $user->setEmail($faker->email);
            $user->setLastname($faker->lastname);
            $user->setFirstname($faker->firstName);
            $user->setAddress($faker->streetAddress);
            //  Pour remplacer les espaces dans la generation de code postaux
            $user->setZipcode(str_replace(' ', '', $faker->postcode));
            $user->setCity($faker->city);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($admin, 'user')
            );
            // dump($user);
        $manager->persist($user);
        }

        $manager->flush();
    }
}
