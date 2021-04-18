<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager)
    {
        for($i=0; $i < 200; $i++){
            $episode = new Episode();
            $faker = Faker\Factory::create('en_US');
            $episode->setNumber($faker->numberBetween(1,30));
            $episode->setTitle($faker->word());
            $episode->setSynopsis($faker->text);
            $episode->setSlug($episode->getTitle());
            $episode->setSeasonId($this->getReference('season_' . rand(0,49)));
            $manager->persist($episode);

        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}