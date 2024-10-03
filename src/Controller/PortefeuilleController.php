<?php

namespace App\Controller;

use App\Entity\Portefeuille;
use App\Entity\Transactions;
use App\Repository\PortefeuilleRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PortefeuilleController extends AbstractController
{
    #[Route('/wallet/{id}', name: 'wallet_show', methods: ['GET'])]
    public function show(Portefeuille $Portefeuille): Response
    {
        // Affiche le porte-monnaie avec son solde et ses transactions
        return $this->render('portefeuille/show.html.twig', [
            'portefeuille' => $Portefeuille,
            'transactions' => $Portefeuille->getTransactions(),
        ]);
    }

    #[Route('/wallet/{id}/transactions', name: 'wallet_transactions', methods: ['GET'])]
    public function transactions(Portefeuille $Portefeuille): Response
    {
        // Affiche l'historique des transactions
        return $this->render('portefeuille/transactions.html.twig', [
            'portefeuille' => $Portefeuille,
            'transactions' => $Portefeuille->getTransactions(),
        ]);
    }

    #[Route('/wallet/{id}/deposit', name: 'wallet_deposit', methods: ['POST'])]
    public function deposit(
        Request $request,
        Portefeuille $Portefeuille,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        NotificationService $notificationService
    ): Response {
        $montant = floatval($request->request->get('montant'));

        // Crée une nouvelle transaction de dépôt
        $transaction = new Transactions();
        $transaction->setType(Transactions::DEPOSIT);
        $transaction->setAmount($montant);
        $transaction->setPortefeuille($Portefeuille);

        // Valide l'objet Transaction
        $errors = $validator->validate($transaction);
        if (count($errors) > 0) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Met à jour le solde du porte-monnaie
        $Portefeuille->setSolde($Portefeuille->getSolde() + $montant);

        $entityManager->persist($transaction);
        $entityManager->flush();

        // Envoyer une notification
        $notificationService->sendNotification("Dépôt de {$montant}€ effectué avec succès sur votre porte-monnaie.");

        return new Response("Dépôt de {$montant}€ effectué ! Solde actuel : " . $Portefeuille->getSolde() . "€.");
    }

    #[Route('/wallet/{id}/withdraw', name: 'wallet_withdraw', methods: ['POST'])]
    public function withdraw(
        Request $request,
        Portefeuille $Portefeuille,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        NotificationService $notificationService
    ): Response {
        $montant = floatval($request->request->get('montant'));

        // Crée une nouvelle transaction de retrait
        $transaction = new Transactions();
        $transaction->setType('retrait');
        $transaction->setAmount($montant);
        $transaction->setPortefeuille($Portefeuille);

        // Valide l'objet Transaction
        $errors = $validator->validate($transaction);
        if (count($errors) > 0) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Vérifie si le solde est suffisant pour retirer le montant demandé
        if ($Portefeuille->getSolde() < $montant) {
            return new Response("Solde insuffisant pour retirer {$montant}€.", Response::HTTP_BAD_REQUEST);
        }

        // Met à jour le solde du porte-monnaie
        $Portefeuille->setSolde($Portefeuille->getSolde() - $montant);

        $entityManager->persist($transaction);
        $entityManager->flush();

        // Envoyer une notification
        $notificationService->sendNotification("Retrait de {$montant}€ effectué avec succès sur votre porte-monnaie.");

        return new Response("Retrait de {$montant}€ effectué ! Solde actuel : " . $Portefeuille->getSolde() . "€.");
    }

    // #[Route('/wallet/{id}/transactions/pdf', name: 'wallet_transactions_pdf', methods: ['GET'])]
    // public function exportPdf(Portefeuille $Portefeuille, \Knp\Snappy\Pdf $knpSnappyPdf): Response
    // {
    //     $html = $this->renderView('portefeuille/transactions_pdf.html.twig', [
    //         'portefeuille' => $Portefeuille,
    //         'transactions' => $Portefeuille->getTransactions(),
    //     ]);

    //     return new Response(
    //         $knpSnappyPdf->getOutputFromHtml($html),
    //         200,
    //         [
    //             'Content-Type' => 'application/pdf',
    //             'Content-Disposition' => 'attachment; filename="transactions.pdf"',
    //         ]
    //     );
    // }
}
