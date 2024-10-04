<?php

namespace App\Controller;

use App\Service\QueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    public function __construct(private QueryService $queryService)
    {
        
    }

    #[Route('/article/{articleId}', name: 'app_article')]
    public function index(int $articleId): Response
    {
        $article = $this->queryService->GetArticle($articleId);
        $user = $this->getUser();

        return $this->render('article/article.html.twig', [
            'article' => $article,
            'user'=> $user
        ]);
    }
}
