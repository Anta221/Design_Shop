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
    public function index( SessionInterface $session , ProduitRepository $repo , PanierRepository $repoPanier): Response
    {
        
        $panier = null ; 
        $produits = null; 
        if($session->has('Panier')){
          $produits = $repo->findArrayProduit(array_keys($session->get('Panier')));
          $panier = $session->get('Panier'); 
        }
        return $this->render('panier/index.html.twig', [
            'produits' => $produits, 
            'panier' => $panier,
            
        ]);
    }

    

    /**
     * Met à jour l'état du panier à true / et met à jour le stock
     * @Route("/edit/panier/" , name="edit_panier")
     */
    public function editPanier(SessionInterface $session , ProduitRepository $repo)
    {
        $em = $this->getDoctrine()->getManager(); 
        $user = $this->getUser(); 

            if($session->has('Panier')){
                $produits = $repo->findArrayProduit(array_keys($session->get('Panier')));
                $panier = $session->get('Panier'); 
              
                
             //Mise à jour des quantité 
                foreach($produits as $item){
                    foreach($panier as $element){
                        if($item->getNom() == $element['nom_produit']){
                            $qt = $item->getStock() - $element['quantite']; 
                            $item->setStock($qt); 
                            foreach($item->getContenuPaniers() as $contenu){
                                $idPanier = $contenu->getPanier();
                                $idPanier->setEtat(1);      
                            }
                    
                            $em->flush(); 
                        }
                    }
                   
                }

                //unset session 
                $session->remove('Panier');

                $this->addFlash("success" , "Votre commande est validé");
            }

            return $this->redirectToRoute('user_commande_show' , [
                'id' => $user->getId()
            ]); 
        
    

    }








}
