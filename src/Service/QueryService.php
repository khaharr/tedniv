<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Articles;

class QueryService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function GetArticle(int $articleId) {
        $articlesRepository = $this->entityManager->getRepository(Articles::class);
        $article = $articlesRepository->findOneBy(['id' => $articleId]);

        return $article;
    }
}