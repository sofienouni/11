<?php

namespace App\Form;

use App\Entity\TypeBien;
use App\Entity\VentesSearch;
use App\Entity\Villes;
use App\Repository\PrixRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentesSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => array(
                    'placeholder' => 'Nom',
                ),
                'label' => false
            ])
            ->add('prenom', null, [
                'attr' => array(
                    'placeholder' => 'Prénom',
                ),
                'label' => false
            ])
            ->add('telephone', null, [
                'attr' => array(
                    'placeholder' => 'Téléphone',
                ),
                'label' => false
            ])
            ->add('ref', null, [
                'attr' => array(
                    'placeholder' => 'Référence',
                ),
                'label' => false
            ])
            ->add('typeBien', EntityType::class, [
                'placeholder' => 'Type Du Bien',
                'label' => false,
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Type Du Bien',
                ),
                'class' => TypeBien::class,
                'multiple' => true,
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
                'multiple' => true,
                'required' => false

            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VentesSearch::class,
        ]);
    }
}
