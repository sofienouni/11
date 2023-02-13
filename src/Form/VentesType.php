<?php

namespace App\Form;

use App\Entity\TypeBien;
use App\Entity\Ventes;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('email', EmailType::class, [
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
            ->add('typebien', EntityType::class, [
                'placeholder' => 'Type Du Bien',
                'attr' => array(
                    'data-placeholder' => 'Type du bien',
                ),
                'class' => TypeBien::class,
                'required' => false

            ])
            ->add('ville', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'label' => false,
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Choisir une ville',
                ),
                'class' => Villes::class,
                'required' => false

            ])
            ->add('message', null, [
                'attr' => array(
                    'placeholder' => 'Laisser votre message ici',
                ),
                'label' => false
            ])
            ->add('photo', FileType::class, [
                'label' => 'Images du Bien',
                'multiple' => true,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes

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
