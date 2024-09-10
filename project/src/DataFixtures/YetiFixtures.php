<?php
namespace App\DataFixtures;

use App\Entity\Yeti;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class YetiFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 10; $i++) {
            $yeti = new Yeti();
            $yeti->setName($faker->name)
                ->setGender($faker->randomElement(['Male', 'Female']))
                ->setHeight($faker->numberBetween(150, 300))
                ->setWeight($faker->numberBetween(50, 150))
                ->setLocation($faker->city)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
            ;

            $manager->persist($yeti);
        }

        $manager->flush();
    }
}

