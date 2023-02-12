<?php

namespace App\Form;

use App\Entity\BiensearchParams;
use App\Entity\Prix;
use App\Entity\TypeBien;
use App\Entity\Villes;
use App\Repository\PrixRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiensearchParamsType extends AbstractType
{

    private $prixRepository;
    public function __construct(PrixRepository $prixRepository){
        $this->prixRepository = $prixRepository;
    }
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
                    'data-placeholder' => 'Choisir Un Type',
                ),
                'class' => TypeBien::class,
                'multiple' => true,
                'required' => false

            ])
            ->add('ville', EntityType::class, [
                'label' => false,
                'class' => Villes::class,
                'multiple' => true,
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Choisir une ville',
                ),
                'required' => false])
            ->add('trie', HiddenType::class)
            ->add('prix', EntityType::class, [
                'placeholder' => 'Prix',
                'label' => false,
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Prix',
                ),
                'class' => Prix::class,
                'required' => false,
                'choices' => $this->prixRepository->findBy(['type' => 1],['min'=>'ASC'])
            ])
            ->add('pieces', ChoiceType::class, [
                'label' => false,
                'multiple' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Nombre de Pièces',
                ),
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6+',
                ],])
            ->add('surface', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Surfaces m2' => 'Surfaces m2',

                    '0 - 50' => '1',
                    '50 - 100' => '2',
                    '100 - 150' => '3',
                    '150 - 200' => '4',
                    '200 - 300' => '5',
                    '300+' => '6',
                ],])
            ->add('ref', null, [
                'attr' => array(
                    'placeholder' => 'Référence',
                ),
                'label' => false
            ])
        ;
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $type = $data['type'];
                if ($type == 'A Vendre'){
                    $type = 0;
                }else{
                    $type = 1;
                }
                $form->add('prix', EntityType::class, [
                    'placeholder' => 'Prix',
                    'label' => false,
                    'attr' => array(
                        'class' => 'chosen-select',
                        'data-placeholder' => 'Prix',
                    ),
                    'class' => Prix::class,
                    'required' => false,
                    'choices' => $this->prixRepository->findBy(['type' => $type],['min'=>'ASC'])

                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BiensearchParams::class,
        ]);
    }
}
