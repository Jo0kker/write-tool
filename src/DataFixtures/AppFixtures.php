<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr_FR');

        for ($u = 0; $u < 10; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'password');
            $user
                ->setFirstName($fake->firstName)
                ->setLastName($fake->lastName)
                ->setEmail($fake->email)
                ->setPassword($hash);
            $manager->persist($user);

            for ($p = 0; $p < mt_rand(1,5); $p++){
                $project = new Project();
                $project
                    ->setUser($user)
                    ->setDescription($fake->sentence(4))
                    ->setName($fake->title);
                $manager->persist($project);

                for ($n = 0; $n < mt_rand(3,7); $n++) {
                    $note = new Note();
                    $note
                        ->setUser($user)
                        ->setProject($project)
                        ->setDescription($fake->sentence(4))
                        ->setContent($fake->text)
                    ;
                    $manager->persist($note);
                }
            }
        }

        $manager->flush();
    }
}
