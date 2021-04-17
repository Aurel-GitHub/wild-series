<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<50;$i++){
            $faker  =  Faker\Factory::create('fr_Fr');
            $name = $faker->name;
            $actor = new Actor();
            $actor->setName($name);
            for($j=0; $j<rand(1,5);$j++){
                $actor->addProgram($this->getReference('program_' . rand(0,5)));
            }

            $manager->persist($actor);

        }

        $manager->flush();
    }

}