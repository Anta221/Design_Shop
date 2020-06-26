<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ancienPassword' , PasswordType::class , [
            'label' => "Veullez saisir votre ancien mot de passe", 
            'required' => true
        ])
        ->add('nouveauPassword' , PasswordType::class , [
            'label' => "Veullez saisir votre nouveau mot de passe", 
            'required' => true
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
