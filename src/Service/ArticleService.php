<?php

namespace App\Service;

use App\Entity\Articles;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\User;

class ArticleService{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function GetArticles () {
        return $this->em->getRepository(Articles::class);
    }
   
}



