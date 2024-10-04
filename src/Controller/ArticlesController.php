<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ArticleService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController extends AbstractController
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    #[Route('/', name: 'app_articles')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Récupérer la requête pour les articles
        $queryBuilder = $this->articleService->GetArticles()->createQueryBuilder('a');

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder, /* Requête ou QueryBuilder */
            $request->query->getInt('page', 1), /* Numéro de page */
            8 /* Nombre d'articles par page */
        );

        return $this->render('articles/articles.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
