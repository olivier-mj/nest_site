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
                'label' => 'Nickame',
                'label_attr' => [
                    'class' => 'flex item-star leading-7 text-sm text-gray-600'
                ],
                'attr' => [
                    'class' => 'w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 
                    ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none focus:border-indigo-500 
                    focus:bg-white focus:ring-2 focus:ring-indigo-200',
                    'required' => true,
                    "minlength" => "3"

                ]
            ])

            ->add('email', EmailType::class, [
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'flex item-star leading-7 text-sm text-gray-600'
                ],
                'attr' => [
                    'class' => 'w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 
                    ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none focus:border-indigo-500 
                    focus:bg-white focus:ring-2 focus:ring-indigo-200',
                    'required' => true,
                    "min" => "12"
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'label_attr' => [
                    'class' => 'flex item-star leading-7 text-sm text-gray-600'
                ],
                'attr' => [
                    'class' => 'w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 
                    ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none focus:border-indigo-500 
                    focus:bg-white focus:ring-2 focus:ring-indigo-200',
                    'required' => true,
                    "min" => "12"
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'flex item-star leading-7 text-sm text-gray-600'
                ],
                'attr' => [
                    'rows' => '6',
                    'class' => 'w-full h-32 px-3 py-1 text-base leading-6 text-gray-700 transition-colors duration-200
                    ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none resize-none focus:border-indigo-500
                    focus:bg-white focus:ring-2 focus:ring-indigo-200',
                    'required' =>  true,
                    "min" => "12"

                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
