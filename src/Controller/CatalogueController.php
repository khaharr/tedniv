<?php

namespace App\Controller;

use App\Service\PriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    private $priceCalculator;

    public function __construct(PriceCalculatorService $priceCalculator)
    {
        $this->priceCalculator = $priceCalculator;
    }

    #[Route('/catalogue', name: 'app_catalogue')]
    public function index(): Response
    {
        // Exemple de catalogue d'articles (normalement, ces données viendraient d'une base de données)
        $catalogue = [
            [
                'nom' => 'T-shirt rouge',
                'prix' => 15,
                'description' => 'T-shirt en coton rouge taille M',
                'image' => 'tshirt-rouge.jpg',
            ],
            [
                'nom' => 'Pantalon jean',
                'prix' => 30,
                'description' => 'Jean slim bleu foncé',
                'image' => 'pantalon-jean.jpg',
            ],
            [
                'nom' => 'Chaussures de sport',
                'prix' => 45,
                'description' => 'Chaussures de sport confortables pour la course',
                'image' => 'chaussures-sport.jpg',
            ],
        ];

        // Calcule le prix TTC pour chaque article
        foreach ($catalogue as &$article) {
            $article['prixTTC'] = $this->priceCalculator->calculatePriceTTC($article['prix']);
        }

        // On passe la variable $catalogue à la vue
        return $this->render('catalogue/catalogue.html.twig', [
            'catalogue' => $catalogue,
        ]);
    }
}
