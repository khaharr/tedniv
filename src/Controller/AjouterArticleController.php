<?php

namespace App\Controller;

use App\Form\AjoutArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Articles;
use Symfony\Component\HttpFoundation\Request;

class AjouterArticleController extends AbstractController
{
    #[Route('/ajouter/aticle', name: 'app_ajouter_aticle', methods: ['GET','POST'])]
    // public function index(): Response
    // {
    //     return $this->render('ajouter_aticle/index.html.twig', [
    //         'controller_name' => 'Khouya ',
    //     ]);
    // }

    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance d'article
        $article = new Articles();

        // Crée le formulaire basé sur un formulaire ArticleType
        $form = $this->createForm(AjoutArticleType::class, $article);

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
        return $this->render('ajouter_article/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
