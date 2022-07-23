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
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white',
                    'placeholder' => 'Type your comment',
                    'style' => "height: 95px"

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
