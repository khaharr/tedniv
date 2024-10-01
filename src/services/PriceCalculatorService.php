<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class PriceCalculatorService
{
    private $logger;
    private $tva;

    public function __construct(LoggerInterface $logger, float $tva)
    {
        $this->logger = $logger;
        $this->tva = $tva;
    }

    /**
     * Calcule le prix TTC d'un produit
     */
    public function calculatePriceTTC(float $priceHT): float
    {
        $priceTTC = $priceHT * (1 + $this->tva / 100);
        
        // Log du calcul
        $this->logger->info('Calcul du prix TTC', [
            'priceHT' => $priceHT,
            'priceTTC' => $priceTTC,
            'tva' => $this->tva
        ]);
        
        return $priceTTC;
    }
}
