<?php
namespace App\Controller;

use App\Entity\Articles;
use App\Entity\User;
use App\Form\MessageFormType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message/{article}/{recipient}', name: 'message')]
    public function showMessages(
        Articles $article, 
        User $recipient, 
        MessageService $messageService, 
        Request $request
    ): Response {
        $sender = $this->getUser(); // Utilisateur connecté
        
        // Récupérer tous les messages entre sender et recipient sur un article
        $messages = $messageService->getMessages($sender, $recipient, $article);

        $form = $this->createForm(MessageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content = $form->get('content')->getData();
            $messageService->sendMessage($sender, $recipient, $article, $content);
            return $this->redirectToRoute('message', [
                'article' => $article->getId(), 
                'recipient' => $recipient->getId()
            ]);
        }

        return $this->render('messages/messages.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),
            'recipient' => $recipient,
            'article' => $article
        ]);
    }
}
