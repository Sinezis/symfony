<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    /*public const SEASONS = [
        [1, 2010, 'Saison 1 de The Walking Dead', 'Walking Dead'],
        [2, 2011, 'Saison 2 de The Walking Dead', 'Walking Dead'],
        [3, 2012, 'Saison 3 de The Walking Dead', 'Walking Dead'],
        [4, 2013, 'Saison 4 de The Walking Dead', 'Walking Dead'],
        [5, 2014, 'Saison 5 de The Walking Dead', 'Walking Dead'],
    ];*/


        public const SEASONS = 5; 

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        foreach (ProgramFixtures::PROGRAM as $programName) {

        for($i = 1; $i <= 5; $i++) {
        $season = new Season();
        $season->setNumber($i);
        $season->setYear($faker->year());
        $season->setDescription($faker->paragraphs(3, true));

        $season->setProgram($this->getReference('program_' . $programName[0]));

        $this->addReference('season_' . $i . "_" . $programName[0], $season);
        $manager->persist($season);
        }
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }


}
