<?php

namespace App\Form;

use App\Entity\Biens;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BiensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('pieces')
            ->add('surface')
            ->add('etat')
            ->add('etage')
            ->add('chauffage')
            ->add('climatisation')
            ->add('ascenceur')
            ->add('concierge')
            ->add('gardien')
            ->add('cideosurveillance')
            ->add('maisongardien')
            ->add('eclairageexterieur')
            ->add('nom')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'A Louer' => true,
                    'A Vendre' => false,
                ],
            ])
            ->add('neuf')
            ->add('typeBien')
            ->add('prix')
            ->add('ville')
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
            'data_class' => Biens::class,
        ]);
    }
}
