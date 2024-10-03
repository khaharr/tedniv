<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ArticleService;

class ArticlesController extends AbstractController
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(): Response
    {
        
        $articles = $this->articleService->GetArticles()->findBy([], null, 100);
        
        return $this->render('articles/articles.html.twig', [
            'articles' => $articles,
            
        ]);
    }

}
