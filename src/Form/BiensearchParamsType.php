<?php

namespace App\Form;

use App\Entity\BiensearchParams;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiensearchParamsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Types de Transaction' => 'Types de Transaction',
                    'A Louer' => 'A Louer',
                    'A Vendre' => 'A Vendre',
                ],])
            ->add('typeBien', ChoiceType::class, [
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
                'class' => Villes::class,
                'placeholder' => 'Choisir une ville',
                'required' => false])
            ->add('prix', ChoiceType::class, [
                'choices' => [
                    'Prix/DT' => 'Prix/DT',
                    '0 - 1000' => '0 - 1000',
                    '1000 - 2000' => '1000 - 2000',
                    '2000 - 5000' => '2000 - 5000',
                    '5000 - 10000' => '5000 - 10000',
                    '+ 10000' => '+ 10000',
                ],])
            ->add('pieces', ChoiceType::class, [
                'choices' => [
                    'Nombre de Pièces' => 'Nombre de Pièces',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6+',
                ],])
            ->add('surface', ChoiceType::class, [
                'choices' => [
                    'Surfaces m2' => 'Surfaces m2',

                    '0 - 50' => '1',
                    '50 - 100' => '2',
                    '100 - 150' => '3',
                    '150 - 200' => '4',
                    '200 - 300' => '5',
                    '300+' => '6',
                ],])
            ->add('neuf', ChoiceType::class, [
                'choices' => [
                    'Bien neuf' => 'Bien neuf',
                    'OUI' => '1',
                    'NON' => '0',
                ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BiensearchParams::class,
        ]);
    }
}