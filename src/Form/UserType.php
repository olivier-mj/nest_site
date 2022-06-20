<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                "label" => false,
            ])
            //            ->add('password')

            ->add('nickname', null, [
                "label" => false,
            ])
            ->add('last_name', null, [
                "label" => false,
            ])
            ->add('first_name', null, [
                "label" => false,
            ])

            ->add('description', null, [
                "label" => false,
                "attr" => [
                    "rows" => "5",
                ],
            ])
            ->add('twitch', null, [
                "label" => false,
            ])
            ->add('twitter', null, [
                "label" => false,
            ])
            ->add('steam', null, [
                "label" => false,
            ])
            ->add('facebook', null, [
                "label" => false,
            ])
            ->add('youtube', null, [
                "label" => false,
            ])
            ->add('instagram', null, [
                "label" => false,
            ])
            // ->add('imageFile', DropzoneType::class, [
            //     'required' => false,
            //     'label' => false,
            //     'attr' => [
            //         'placeholder' => 'Drag and drop or browse',
            //         'data-controller' => 'mydropzone'
            //     ],
            // ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => false,

                'attr' => [
                    'class'=> 'hidden',
                    'accept' => '.jpg, .jpeg, .png',
                ],
            ])
            ->add('save', SubmitType::class, [
                "attr" => [
                    "class" => "inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
