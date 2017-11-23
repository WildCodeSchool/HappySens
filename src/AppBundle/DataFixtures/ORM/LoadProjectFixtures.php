<?php
/**
 * Created by PhpStorm.
 * User: aurelie
 * Date: 22/11/17
 * Time: 14:30
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\ORM\LoadUserFixtures;
use AppBundle\Entity\Project;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectFixtures extends Fixture implements FixtureInterface
{
    const MAX = 10;

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create("fr_FR");
        $project = [];

        for($i = 0; $i <= self::MAX; $i++) {
            $project[$i] = new Project();
            $project[$i]->setTitle($faker->sentence($nbWords = 5, $variableNbWords = true));
            $project[$i]->setStartingDate($faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = date_default_timezone_get()));
            $project[$i]->setEndDate($faker->dateTimeBetween($startDate = 'now', $endDate = '+2 years', $timezone = date_default_timezone_get()));
            $project[$i]->setPresentation($faker->sentence($nbWords = 75, $variableNbWords = true));
            $project[$i]->setProfit($faker->sentence($nbWords = 20, $variableNbWords = true));
            $project[$i]->setBeneficeCompany($faker->sentence($nbWords = 30, $variableNbWords = true));
            $project[$i]->setAuthor($this->getReference("user-" . $i));
            $manager->persist($project[$i]);
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return array(
            LoadUserFixtures::class,
        );
    }
}
