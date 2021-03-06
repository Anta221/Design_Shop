<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $repoProduit)
    {

        return $this->render('home/index.html.twig' , [
            'produits' => $repoProduit->findAll(),
        ]);
    }
}
