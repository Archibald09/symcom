<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FullName', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir votre nom'
                ]
            ])
            ->add('adress', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre adresse de facturation'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'required' => false,
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Ex: 75000'
                ]
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ex: Paris'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
