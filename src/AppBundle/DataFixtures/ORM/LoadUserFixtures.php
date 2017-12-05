<?php
/**
 * Created by PhpStorm.
 * User: aurelie
 * Date: 22/11/17
 * Time: 14:30
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Service\SlugService;
use Faker;
use AppBundle\DataFixtures\ORM\LoadCompanyFixtures;
use AppBundle\DataFixtures\ORM\LoadUserHasSkillFixtures;
use AppBundle\DataFixtures\ORM\LoadSkillFixtures;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserFixtures extends Fixture implements FixtureInterface
{
    const ROLE = [
        1 => 1,
        2 => 5,
        3 => 25,
        4 => 5,
        5 => 4
    ];

    public function load(ObjectManager $manager) {
        $slugService = new SlugService();
        $faker = Faker\Factory::create("fr_FR");
        $user = [];
        $nbUser = 0;
        foreach (self::ROLE as $key => $role) {
            for ($i = 0; $i < $role; $i++) {
                $firstName = $faker->firstName();
                $lastName = $faker->lastName();
                $user[$nbUser] = new User();
                $user[$nbUser]->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setPhone($faker->phoneNumber)
                    ->setEmail($faker->email)
                    ->setStatus($key)
                    ->setBirthdate($faker->dateTime($max = 'now', $timezone = date_default_timezone_get()))
                    ->setPhoto($faker->imageUrl("150", "150"))
                    ->setBiography($faker->realText($maxNbChars = 255, 2))
                    ->setSlogan($faker->realText($maxNbChars = 255, 2))
                    ->setPassword('$2y$13$A7/nsWsNOh4GaCeD4bAv3uL4uMZBPBWz1x00/MCh4d8/Xcs4SjamO')
                    ->setMood(rand(1, 5))
                    ->setJob($faker->jobTitle)
                    ->setWorkplace($faker->city)
                    ->setNativeLanguage("FR")
                    ->setFacebook($faker->url)
                    ->setTwitter($faker->url)
                    ->setLinkedin($faker->url)
                    ->setLanguage($faker->randomElement($array = ["Anglais", "Espagnol", "Russe", "Polonais", "Vietnamien", "Japonais"]))
                    ->setSlug($slugService->slugify($firstName . ' ' . $lastName));
                if ($key != 2 and $key!= 3) {
                    $user[$nbUser]->setIsActive(1);
                }
                if ($key === 2) {
                    $user[$nbUser]->setCompany($this->getReference("company-" . $i));
                    $user[$nbUser]->setIsActive(rand(0,1));
                }
                if ($key === 3) {
                    $user[$nbUser]->setCompany($this->getReference("company-" . rand(0, self::ROLE[2] - 1)));
                    $user[$nbUser]->setIsActive(rand(0,1));
                }
                $manager->persist($user[$nbUser]);
                $this->addReference("user-" . $nbUser, $user[$nbUser]);
                $nbUser++;
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadSkillFixtures::class,
            LoadCompanyFixtures::class,
        );
    }
}