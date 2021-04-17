<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number' , NumberType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'numéro de la série'
                ]
            ])
            ->add('year', NumberType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'année'
                ]
            ])
            ->add('description', TextareaType::class , [
                'label' => false,
                'attr' => [
                    'placeholder' => 'description'
                ]
            ])
            ->add('program_id', null, ['choice_label' => 'title'])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter', 
                'attr' => [
                    'class' => 'btn-info btn-sm' 
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
