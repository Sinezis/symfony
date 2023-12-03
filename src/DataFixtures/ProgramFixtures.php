<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    /*public const PROGRAM = [
        ['Walking Dead', 'Des zombies envahissent la terre', 'Horreur'],
        ['Game of Throne', 'Des morts vivants envahissent Westeros', 'Fantastique'],
        ['Breaking Bad', 'Meilleure série de tous les temps', 'Action'],
        ['Arcane', 'Série d\'animation dans l\'univers de Riot', 'Animation'],
        ['The haunting of Hill House', 'Une famille revit ses traumas', 'Horreur'],
        ['Fondation', 'Les mathématiques prévoient le futur', 'SciFi'],
        ['Moon Knight', 'Un gardien de musée devient super héros', 'Aventure'],
        ['Ob-Wan Kenobi', 'Vous le connaissez  ', 'Aventure'],
        ['The Expanse', 'L\'humanité a concquit la galaxie', 'SciFi'],
        ['Altered Carbon', 'Une dystopie transhumaniste', 'SciFi'],
        ['Cyberpunk', 'Une dystopie transhumaniste', 'Animation'],
        ['Midnight Mass', 'Des vampires sur une ile', 'Horreur'],
        ['Hannibal', 'L\'histoire d\'Hanibal Lecter', 'Horreur']
    ];*/
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i < 10; $i++) {
        $program = new Program();
        $program->setTitle($faker->word());
        $program->setSynopsis($faker->words(10, true));
        $program->setCategory($this->getReference('category_' . $faker->numberBetween(0,6)));
        $this->addReference('program_' . $i, $program);
        $manager->persist($program);
        $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }


}

