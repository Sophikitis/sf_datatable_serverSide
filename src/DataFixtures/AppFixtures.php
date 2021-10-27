<?php

namespace App\DataFixtures;

use App\Entity\Candidates;
use App\Entity\Tags;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;

class AppFixtures extends Fixture
{

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=1; $i <= 2000; $i++){
            $candidate = new Candidates();
            $candidate->setFirstname($faker->firstName);
            $candidate->setLastname($faker->lastName);

            for ($j=1; $j <= 10; $j++){
                $tags = new Tags();
                $tags->setTag($faker->citySuffix);
                $tags->setValue($faker->city);
                $candidate->addTag($tags);

                $manager->persist($tags);
            }

            $manager->persist($candidate);
        }


        $user = new User();
        $user->setUsername('jerem');

        $manager->persist($user);

        $manager->flush();
    }
}
