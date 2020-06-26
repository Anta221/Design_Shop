<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\ContenuPanier;
use App\Form\ContenuPanierType;
use App\Repository\ProduitRepository;
use App\Repository\ContenuPanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contenu/panier")
 */
class ContenuPanierController extends AbstractController
{
 
    /**
     * Permet d'ajouter un produit dans la session Panier
     * @Route("/new/{id}", name="contenu_panier_new", methods={"GET","POST"})
     */
    public function new(Request $request , Produit $produit , SessionInterface $session ,ContenuPanierRepository $repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        //création de la session si ,existe pas 
        if(!$session->has('Panier')){

            $session->set('Panier', array()); 
            $panier =   $session->get('Panier');

            //contenu panier
            $contenuPanier = new ContenuPanier();
            $contenuPanier->setQuantite($request->request->get('quantite')); 
            $contenuPanier->setCreatedAt(new \DateTime()); 
            $produit->addContenuPanier($contenuPanier); 

        
            // Création du panier
            $panierUser = new Panier(); 
            $panierUser->setUser($this->getUser()); 
            $panierUser->setCreatedAt(new \DateTime()); 
            $panierUser->setEtat(0); 
            $entityManager->persist( $panierUser); 
    

            $id_produit = $produit->getId(); 
            $panier[$id_produit] = [
                'id_produit' => $produit->getId(),
                'quantite' => $contenuPanier->getQuantite(), 
                'date_ajout' => $contenuPanier->getCreatedAt(), 
                'prix' =>$produit->getPrix(), 
                'stock' => $produit->getStock(), 
                'nom_produit' => $produit->getNom()
            ]; 

            $session->set('Panier' , $panier); 

        }else{

            $panier = $session->get('Panier'); 
            $contenuPanier = new ContenuPanier();
            $contenuPanier->setQuantite($request->request->get('quantite')); 
            $contenuPanier->setCreatedAt(new \DateTime()); 
            $produit->addContenuPanier($contenuPanier); 

            $id_produit = $produit->getId(); 
            if(array_key_exists($id_produit , $panier)){

                $quantite = $panier[$id_produit]['quantite'] + $contenuPanier->getQuantite(); 
                $panier[$id_produit]['quantite'] = $quantite;  
               
            }else{

                $panier[$id_produit] = [
                    'id_produit' => $produit->getId(),
                    'quantite' => $contenuPanier->getQuantite(), 
                    'date_ajout' => $contenuPanier->getCreatedAt(), 
                    'prix' =>$produit->getPrix(), 
                    'stock' => $produit->getStock(), 
                    'nom_produit' => $produit->getNom()
                ]; 


            }
      
            $session->set('Panier' ,  $panier ); 

        }

   

        $entityManager->persist($contenuPanier);
       
        $entityManager->flush();

 
        $this->addFlash("success" , "Produit ajouté au panier");

        return $this->redirectToRoute('panier_index' );

        return $this->render('contenu_panier/new.html.twig', [
            'contenu_panier' => $contenuPanier,
            'form' => $form->createView(),
        ]);
    }




    /**
     * Affiche le contenu du panier
     * @Route("/", name="contenu_panier_show", methods={"GET"})
     */
    public function show(SessionInterface $session , ProduitRepository $repo): Response
    {
          // récupération des modèles hauts en session
          $panier = null ; 
          $produits = null; 
          if($session->has('Panier')){
            $produits = $repo->findArrayProduit(array_keys($session->get('Panier')));
            $panier = $session->get('Panier'); 
          }
    
        return $this->render('contenu_panier/show.html.twig', [
            'produits' => $produits, 
            'panier' => $panier
        ]);
    }





    /**
     * Supprime un produit de la sesion panier
     * @Route("/delete/produit/panier/{id}", name="delete_produit_panier")
     * @param SessionInterface $session
     * @param $id
     * @return Response
     */
    public function deleteProduit(SessionInterface $session, $id):Response
    {
        $panier = $session->get('Panier');

        if(array_key_exists($id, $panier))
        {
            unset($panier[$id]);
            $session->set('Panier', $panier);
        }
        $this->addFlash('danger', 'Produit supprimé');
       return $this->redirectToRoute('panier_index');

    }




}
