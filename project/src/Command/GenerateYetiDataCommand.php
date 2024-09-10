<?php

namespace App\Command;

use App\Entity\Yeti;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Factory as FakerFactory;

#[AsCommand(
    name: 'app:generate-yeti-data',
    description: 'Generates random Yeti data.',
)]
class GenerateYetiDataCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = FakerFactory::create();
        $manager = $this->entityManager;

        for ($j = 0; $j < 10; $j++) {

                $yeti = new Yeti();
                $yeti->setName($faker->name)
                    ->setGender($faker->randomElement(['Male', 'Female', 'Other']))
                    ->setHeight($faker->numberBetween(150, 300))
                    ->setWeight($faker->numberBetween(50, 150))
                    ->setLocation($faker->city)
                    ->setRating($faker->numberBetween(1, 5));

                $creationTime = (new \DateTimeImmutable())->add(new \DateInterval('PT' . $j . 'S'));
                $yeti->setCreatedAt($creationTime)
                    ->setUpdatedAt($creationTime);

                $manager->persist($yeti);

        }

        $manager->flush();

        $output->writeln('Yeti data generated successfully!');

        return Command::SUCCESS;
    }
}
