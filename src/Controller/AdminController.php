<?php

namespace App\Controller;

use App\Repository\PanierRepository;
use App\Repository\ContenuPanierRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/super_admin", name="panier_liste")
     */
    public function index(PanierRepository $repo , ContenuPanierRepository $repoContenu)
    {
        return $this->render('admin/index.html.twig', [
            'paniers' => $repo->findBy([
                'etat' => 0
            ]),
            'contenu' => $repoContenu->findAll()
        ]);
    }




}
