<?php

namespace App\Form;

use App\Entity\Biens;
use App\Entity\TypeBien;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('description',CKEditorType::class)
            ->add('pieces')
            ->add('surface')
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
            ->add('ref', null, [
                'required'   => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'A Louer' => true,
                    'A Vendre' => false,
                ],
            ])
            ->add('neuf')
            ->add('typebien', EntityType::class, [
                'placeholder' => 'Type Du Bien',
                'attr' => array(
                    'data-placeholder' => 'Type du bien',
                ),
                'class' => TypeBien::class,
                'required' => false

            ])
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
