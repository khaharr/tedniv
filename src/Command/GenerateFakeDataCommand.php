<?php

// src/Command/GenerateFakeDataCommand.php
namespace App\Command;

use App\Entity\Articles; // Importer votre entité Articles
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFakeDataCommand extends Command
{
    protected static $defaultName = 'app:generate-fake-data';
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Génère des données fictives pour les articles.')
            ->setName('app:generate-fake-data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $faker = Factory::create();
            $numberOfEntries = 100;

            for ($i = 0; $i < $numberOfEntries; $i++) {
                $article = new Articles();
                $article->setTitle(implode($faker->words(3))); // Utilisation de words() à la place
                $article->setPrice($faker->randomFloat(2, 5, 100)); // Prix fictif
                $article->setDescription($faker->paragraph()); // Description fictive
                $article->setUUID($faker->uuid()); // UUID fictif
                $article->setImg([$faker->imageUrl(640, 480, 'cats', true)]); // Image fictive

                $this->entityManager->persist($article);
            }

            $this->entityManager->flush();

            $output->writeln("$numberOfEntries articles générés avec succès !");
        } catch (\Exception $e) {
            $output->writeln('Une erreur est survenue : ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
