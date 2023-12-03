<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    /*public const Episodes = [
        [1, ' Days Gone Bye', 'Saison 1 de The Walking Dead','Le shérif adjoint Rick Grimes se réveille d\'un coma et cherche sa famille dans un monde ravagé par les morts-vivants.' ],
        [2, 'Guts', 'Saison 1 de The Walking Dead', 'In Atlanta, Rick is rescued by a group of survivors, but they soon find themselves trapped inside a department store surrounded by walkers.'],
        [3, 'Tell It to the Frogs', 'Saison 1 de The Walking Dead', 'Rick is reunited with Lori and Carl but soon decides - along with some of the other survivors - to return to the rooftop and rescue Merle. Meanwhile, tensions run high between the other survivors at the camp.'],
        [4, 'Vatos', 'Saison 1 de The Walking Dead', 'Rick, Glenn, Daryl and T-Dog come across a group of seemingly hostile survivors whilst searching for Merle. Back at camp, Jim begins behaving erratically.'],
        [5, 'Wildfire', 'Saison 1 de The Walking Dead', 'After the attack on the camp, Rick leads the survivors to the C.D.C., in the hope that they can cure an infected Jim.'],
        [6, 'TS-19', 'Saison 1 de The Walking Dead', 'The survivors gain access to the C.D.C. in the hope of a safe haven.']
    ];*/

    /*public function load(ObjectManager $manager)
    {
        foreach (self::Episodes as $info) {
        $episode = new Episode();
        $episode->setNumber($info[0]);
        $episode->setTitle($info[1]);
        $episode->setSynopsis($info[3]);
        $episode->setSeason($this->getReference('season_' . $info[2]));
        $this->addReference('episode_' . $info[1], $episode);
        $manager->persist($episode);
        $manager->flush();
        }
    }*/

    public function load(ObjectManager $manager) {
        $faker = Factory::create();

        for($i = 0; $i < 500; $i++) {
            $episode = new Episode();

            $episode->setNumber($faker->numberBetween(1, 10));
            $episode->setTitle($faker->words (7, true));
            $episode->setSynopsis($faker->words (20, true));
            $episode->setSeason($this->getReference('season_' . ($i % 50)));
            
            $manager->persist($episode);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
          ProgramFixtures::class,
          SeasonFixtures::class,
        ];
    }/**/

}
