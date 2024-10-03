<?php

namespace App\Controller;

use App\Entity\Portefeuille;
use App\Entity\Transactions;
use App\Enum\TransactionType;
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
    public function show(Portefeuille $portefeuille): Response
    {
        // Affiche le porte-monnaie avec son solde et ses transactions
        return $this->render('portefeuille/show.html.twig', [
            'portefeuille' => $portefeuille,
            'transactions' => $portefeuille->getTransactions(),
        ]);
    }

    #[Route('/wallet/{id}/transactions', name: 'wallet_transactions', methods: ['GET'])]
    public function transactions(Portefeuille $portefeuille): Response
    {
        // Affiche l'historique des transactions
        return $this->render('portefeuille/transactions.html.twig', [
            'portefeuille' => $portefeuille,
            'transactions' => $portefeuille->getTransactions(),
        ]);
    }

    #[Route('/wallet/{id}/update', name: 'wallet_update', methods: ['POST'])]
    public function update(
        Request $request,
        Portefeuille $portefeuille,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        NotificationService $notificationService
    ): Response {
        $amount = floatval($request->request->get('amount'));
        $type = $request->request->get('type');

        // Crée une nouvelle transaction de dépôt
        $transaction = new Transactions();
        $transaction->setAmount($amount);
        $transaction->setPortefeuille($portefeuille);
        if ($type === 'deposit') {
            $transaction->setType(TransactionType::DEPOSIT);
            $portefeuille->setSolde($portefeuille->getSolde() + $amount);
        } elseif ($type === 'withdraw') {
            $transaction->setType(TransactionType::WITHDRAW);
            if ($portefeuille->getSolde() < $amount) {
                return new Response("Solde insuffisant pour retirer {$amount}€.", Response::HTTP_BAD_REQUEST);
            }
            $portefeuille->setSolde($portefeuille->getSolde() - $amount);
        } else {
            return new Response("Type de transaction invalide : " . $type, Response::HTTP_BAD_REQUEST);
        }

        // Valide l'objet Transaction
        $errors = $validator->validate($transaction);
        if (count($errors) > 0) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($transaction);
        $entityManager->flush();

        // Envoyer une notification
        // $notificationService->sendNotification("Dépôt de {$amount}€ effectué avec succès sur votre porte-monnaie.");

        return new Response(($type === 'deposit' ? "Dépôt " : "Retrait ") . "de {$amount}€ effectué ! Solde actuel : " . $portefeuille->getSolde() . "€.");
    }

    #[Route('/wallet/{id}/withdraw', name: 'wallet_withdraw', methods: ['POST'])]
    public function withdraw(
        Request $request,
        Portefeuille $Portefeuille,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        NotificationService $notificationService
    ): Response {
        $amount = floatval($request->request->get('amount'));

        // Crée une nouvelle transaction de retrait
        $transaction = new Transactions();
        $transaction->setType(TransactionType::WITHDRAW);
        $transaction->setAmount($amount);
        $transaction->setPortefeuille($Portefeuille);

        // Valide l'objet Transaction
        $errors = $validator->validate($transaction);
        if (count($errors) > 0) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Vérifie si le solde est suffisant pour retirer le amount demandé
        if ($Portefeuille->getSolde() < $amount) {
            return new Response("Solde insuffisant pour retirer {$amount}€.", Response::HTTP_BAD_REQUEST);
        }

        // Met à jour le solde du porte-monnaie
        $Portefeuille->setSolde($Portefeuille->getSolde() - $amount);

        $entityManager->persist($transaction);
        $entityManager->flush();

        // Envoyer une notification
        // $notificationService->sendNotification("Retrait de {$amount}€ effectué avec succès sur votre porte-monnaie.");

        return new Response("Retrait de {$amount}€ effectué ! Solde actuel : " . $Portefeuille->getSolde() . "€.");
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
