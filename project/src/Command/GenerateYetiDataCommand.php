<?php

namespace App\Command;

use App\Entity\Yeti;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
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

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = FakerFactory::create();
        $manager = $this->entityManager;

        for ($i = 0; $i < 10; $i++) {
            $yeti = new Yeti();
            $yeti->setName($faker->name)
                ->setGender($faker->randomElement(['Male', 'Female']))
                ->setHeight($faker->numberBetween(150, 300))
                ->setWeight($faker->numberBetween(50, 150))
                ->setLocation($faker->city)
//                ->setCreatedAt(new \DateTimeImmutable())
//                ->setUpdatedAt(new \DateTimeImmutable())
            ;

            $manager->persist($yeti);
        }

        $manager->flush();

        $output->writeln('Yeti data generated successfully!');

        return Command::SUCCESS;
    }
}
