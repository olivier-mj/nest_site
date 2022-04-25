<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Your nick name',
                    'class' => 'form-control shadow-sm',
                    'required' => true,
                    "minlength" => "3"

                ]
            ])

            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Your address email',
                    'class' => 'form-control shadow-sm',
                    'required' => true,
                    "min" => "12"
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Subject',
                    'class' => 'form-control shadow-sm',
                    'required' => true,
                    "min" => "12"

                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => '4',
                    'placeholder' => 'Your message',
                    'class' => 'form-control shadow-sm',
                    'required' =>  true,
                    "min" => "12"

                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
