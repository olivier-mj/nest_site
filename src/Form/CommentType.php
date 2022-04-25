<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('name', TextType::class, [
//                'label' => false,
//                'attr' => [
//                    'placeholder' => 'Name',
//                    'class' => 'form-control shadow-sm',
//                    'required' => true,
//                    "minlength" => "3"
//
//                ]
//            ])
//            ->add('email', EmailType::class, [
//                'label' => false,
//                'attr' => [
//                    'placeholder' => 'Email Address (will not published)',
//                    'class' => 'form-control shadow-sm',
//                    'required' => true,
//                    "minlength" => "8"
//
//                ]
//            ])
            ->add('content',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control shadow-sm',
                    'placeholder' => 'Type your comment',

                ],
            ] )
            ->add('submit',SubmitType::class, [
                'label' =>'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary   my-3'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}