<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Articles;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'liste_articles')]
    
    public function listArticles(EntityManagerInterface $entityManager): Response
    {
        // Récupérer le repository de l'entité Article
        $articleRepository = $entityManager->getRepository(Articles::class);

        // Récupérer tous les articles
        $articles = $articleRepository->findAll();

        // Rendre la vue avec les articles
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
