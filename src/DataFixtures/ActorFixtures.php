<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i=0; $i < 10; $i++) {
        $actor = new Actor();
        $actor->setName($faker->firstName() . ' ' . $faker->lastName());
        $actor->addProgram($this->getReference('program_' . ProgramFixtures::PROGRAM[array_rand(ProgramFixtures::PROGRAM)][0]));
        $actor->addProgram($this->getReference('program_' . ProgramFixtures::PROGRAM[array_rand(ProgramFixtures::PROGRAM)][0]));
        $actor->addProgram($this->getReference('program_' . ProgramFixtures::PROGRAM[array_rand(ProgramFixtures::PROGRAM)][0]));
        
        $manager->persist($actor);
        $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }


}