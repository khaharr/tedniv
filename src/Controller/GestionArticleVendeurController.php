<?php

namespace App\Controller;

use App\Form\GestionArticleVendeurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Articles;
use Symfony\Component\HttpFoundation\Request;

#[Route('/gestion')]
class GestionArticleVendeurController extends AbstractController
{

    #[Route(name:'app_gestion_article')]
    public function listArticles(EntityManagerInterface $entityManager): Response
    {
        // Récupérer le repository de l'entité Article
        $articleRepository = $entityManager->getRepository(Articles::class);

        // Récupérer tous les articles
        $articles = $articleRepository->findAll();

        // Rendre la vue avec les articles
        return $this->render('gestion_article_vendeur/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/ajouter', name: 'app_ajouter_aticle', methods: ['GET','POST'])]
    

    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance d'article
        $article = new Articles();

        // Crée le formulaire basé sur un formulaire ArticleType
        $form = $this->createForm(GestionArticleVendeurType::class, $article);

        // Traite la requête
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Associe l'utilisateur connecté à l'article
            //$article->setUser($this->getUser());

            // Persist et flush l'article dans la base de données
            $entityManager->persist($article);
            $entityManager->flush();

            // Redirection après ajout
            return $this->redirectToRoute('liste_articles');
        }

        // Affiche le formulaire dans la vue
        return $this->render('gestion_article_vendeur/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_article_affiche', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('gestion_article_vendeur/affiche.html.twig', [
            'article' => $article,
        ]);
    }
    
    #[Route('/{id}/modification', name: 'app_article_modification', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GestionArticleVendeurType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion_article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion_article_vendeur/modification.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_suppression', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_article', [], Response::HTTP_SEE_OTHER);
    }
}
