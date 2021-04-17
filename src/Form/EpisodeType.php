<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' , TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'titre'
                ]
            ])
            ->add('number', NumberType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'numéro'
                ]
            ])
            ->add('synopsis', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'synopsis'
                ]
            ])
            ->add('season_id', null, ['choice_label' => 'title'])
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
            'data_class' => Episode::class,
        ]);
    }
}
