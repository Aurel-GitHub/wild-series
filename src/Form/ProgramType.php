<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                          
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'title'
                ]
            ])
            ->add('summary', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'summary'
                ]

            ])
            ->add('poster', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'paste your link of poster here'
                ]
            ])
            // ->add('category', null, ['choice_label' => 'name'])
            ->add('category', EntityType::class,  [
                'label' => false,
                'class' => Category::class,
                'choice_label' => 'name'
            ])
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
            'data_class' => Program::class,
        ]);
    }                                     
}
