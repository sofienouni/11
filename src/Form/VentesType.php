<?php

namespace App\Form;

use App\Entity\Ventes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => array(
                    'placeholder' => 'Nom',
                ),
                'required'   => true,
                'label' => false
            ])
            ->add('prenom', null, [
                'attr' => array(
                    'placeholder' => 'Prénom',
                ),
                'required'   => true,
                'label' => false
            ])
            ->add('email', null, [
                'attr' => array(
                    'placeholder' => 'Email',
                ),
                'required'   => true,
                'label' => false
            ])
            ->add('telephone', null, [
                'attr' => array(
                    'placeholder' => 'Téléphone',
                ),
                'required'   => true,
                'label' => false
            ])
            ->add('operation', ChoiceType::class, [
                'choices' => [
                    'A Louer' => 'A Louer',
                    'A Vendre' => 'A Vendre',
                ],
                'label' => false])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Types Du Bien' => 'Types Du Bien',
                    'Appartement' => 'Appartement',
                    'Maison' => 'Maison',
                    'Terrain' => 'Terrain',
                    'Commerce' => 'Commerce',
                    'Garage/Parking' => 'Garage/Parking',
                    'Immeuble' => 'Immeuble',
                    'Bureau' => 'Bureau',
                    'Cave' => 'Cave',
                ],
                'label' => false])
            ->add('ville', null, [
                'attr' => array(
                    'placeholder' => 'Ville',
                ),
                'label' => false
            ])
            ->add('message', null, [
                'attr' => array(
                    'placeholder' => 'Laisser votre message ici',
                ),
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ventes::class,
        ]);
    }
}
