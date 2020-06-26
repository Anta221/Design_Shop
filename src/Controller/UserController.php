<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Entity\ChangePassword;
use App\Form\ChangePasswordType;
use App\Form\UserEditPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    

    /**
     * Inscription d'un utilisateur
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request ,  UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Encodage du mot de passe
            if($user->getPassword() != null ){      
                $hash = $passwordEncoder->encodePassword($user , $user->getPassword());
                $user->setPassword($hash); 
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash( "success" , "Félicitation ".$user->getPrenom() . " votre compte a été créer. Vous pouvez vous connecter"); 

            
            return $this->redirectToRoute('user_show' , [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Permet de voir son compte utilisateur
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * Mettre à jour ses informations
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success" , "Mise à jour résussite"); 
            return $this->redirectToRoute('user_show ' , [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    /**
     * Mettre à jour son mot de passe
     * @Route("/user/edit/password/{id}", name="user_edit_password", methods={"GET","POST"})
     */
    public function changePassword(Request $request, User $user ,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $changePassword = new ChangePassword();

        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        $user = $this->getUser(); 
        $erreur = null ; 
 

        if ($form->isSubmitted() && $form->isValid()) {

            $ancienPassword = $changePassword->getAncienPassword(); 

            //véification si ancien mot de passe valide
            $verif = \password_verify($ancienPassword , $user->getPassword());  
    
            if($verif == true){
                $newPassword = $changePassword->getNouveauPassword(); 

        
                    $hash = $passwordEncoder->encodePassword($user , $newPassword);
                    $user->setPassword($hash); 

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
    
                    $this->addFlash('success' , 'Mot de passe modifié'); 
    
                    return $this->redirectToRoute('user_show' , [
                        'id' => $user->getId()
                    ]);

            }else{
                $erreur = "Veuillez saisir votre ancien mot de passe n'a été correctement saisi"; 
            }

        }

        return $this->render('user/change-password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'erreur' => $erreur 
        ]);
    }





    /**
     * Permet de supprimer son compte
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            //supprime la session de l'utilisateur
            $this->container->get('security.token_storage')->setToken(null);

            $this->addFlash("danger" , "Votre compte a bien été supprimé"); 
        }

        return $this->redirectToRoute('home');
    }
}
