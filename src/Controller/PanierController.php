<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user/panier")
 */
class PanierController extends AbstractController
{


    /**
     * AFFICHE LES PRODUITS STOCKÉ DANS LE PANIER
     * @Route("/", name="panier_index", methods={"GET"})
     */
    public function index( SessionInterface $session , ProduitRepository $repo): Response
    {
        // récupération des modèles hauts en session
        $produits = $repo->findArrayProduit(array_keys($session->get('Panier')));
    
        return $this->render('panier/index.html.twig', [
            'produits' => $produits, 
            'panier' => $session->get('Panier')
        ]);
    }

    

    /**
     * Affiche la liste des paniers
     * @Route("/liste/panier" , name="panier_liste" )
     */
    public function listePanier(PanierRepository $repo)
    {
        return $this->render('panier/liste.html.twig', [
            'panier' => $repo->findAll()
        ]);

    }


    /**
     * Met à jour l'état du panier à true / et met à jour le stock
     * @Route("/edit/panier/{id}" , name="edit_panier")
     */
    public function editPanier(Panier $panier , SessionInterface $session )
    {
        $em = $this->getDoctrine()->getManager(); 
        if($panier->getUser() == $this->getUser()){
            $panier->setEtat(1); 
           
            $this->addFlash("success" , "Votre commande est validé"); 
        }

    }





}
