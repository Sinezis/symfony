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
        [6, 2015, 'Saison 6 de The Walking Dead', 'Walking Dead'],
        [7, 2016, 'Saison 7 de The Walking Dead', 'Walking Dead'],
        [8, 2017, 'Saison 8 de The Walking Dead', 'Walking Dead'],
        [9, 2018, 'Saison 9 de The Walking Dead', 'Walking Dead'],
        [10, 2019, 'Saison 10 de The Walking Dead', 'Walking Dead'],
        [11, 2020, 'Saison 11 de The Walking Dead', 'Walking Dead'],
    ];*/

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i < 50; $i++) {
        $season = new Season();
        $season->setNumber($faker->numberBetween(1, 10));
        $season->setYear($faker->year());
        $season->setDescription($faker->paragraphs(3, true));

        $season->setProgram($this->getReference('program_' . ($i % 10)));

        $this->addReference('season_' . $i, $season);
        $manager->persist($season);
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
