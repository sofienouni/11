<?php

namespace App\Form;

use App\Entity\BienSearch;
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

class BienSearchType extends AbstractType
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
        ;

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                if (isset($data['type'])){
                    $type = $data['type'];
                    if ($type == 'A Vendre') {
                        $type = 0;
                    } else {
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
                        'choices' => $this->prixRepository->findBy(['type' => $type], ['min' => 'ASC'])

                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BienSearch::class,
            'listes_prix_location' => null
        ]);
    }
}
