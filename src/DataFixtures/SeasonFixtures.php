<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 50; $i++) {
            $faker  =  Faker\Factory::create('fr_Fr');
            $season = new Season();
            $season->setNumber($faker->numberBetween(1,10));
            $season->setDescription($faker->text);
            $season->setYear($faker->year);
            $season->setDescription($faker->text);
            $season->setProgramId($this->getReference('program_' . rand(0,5)));
            $this->addReference('season_' . $i, $season);
            $manager->persist($season);

        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}