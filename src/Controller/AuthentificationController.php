<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    /**
    * Permet au utilisateur de se connecter
    * @Route("/login" , name="login" , methods={"GET","POST"})
    */
    public function login(AuthenticationUtils $utils){

        $error = $utils->getLastAuthenticationError(); 
        $username = $utils->getLastUsername(); 

 
        return $this->render('authentification/index.html.twig' , [
            'hasError' => $error != null, 
            'username' => $username
        ]);
        
    }


    /** 
     * Déconnexion
     * @Route("/logout" , name="logout")
     */
    public function logout(){}
}
