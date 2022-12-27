<?php

namespace App\Form;

use App\Entity\BienSearch;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Types de Transaction' => 'Types de Transaction',
                    'A Louer' => 'A Louer',
                    'A Vendre' => 'A Vendre',
                ],])
            ->add('typeBien', ChoiceType::class, [
                'label' => false,
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
                ],])
            ->add('ville', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'label' => false,
                'class' => Villes::class,

            ])
            ->add('prix', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Prix/DT' => 'Prix/DT',
                    '0 - 1000' => '0 - 1000',
                    '1000 - 2000' => '1000 - 2000',
                    '2000 - 5000' => '2000 - 5000',
                    '5000 - 10000' => '5000 - 10000',
                    '+ 10000' => '+ 10000',
                ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BienSearch::class,
        ]);
    }
}
