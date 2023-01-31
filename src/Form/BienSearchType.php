<?php

namespace App\Form;

use App\Entity\BienSearch;
use App\Entity\TypeBien;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('typeBien', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'label' => false,
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Choisir une ville',
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
            ->add('ref', null, [
                'attr' => array(
                    'placeholder' => 'Référence',
                ),
                'label' => false
            ])
            ->add('trie', HiddenType::class)
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
