<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'download_label' => false,
                'label' => 'logo',
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png',
                ],
            ])

            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'autofocus' => false,
                    'placeholder' => 'Title here',
                    'class' => ''
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'rows' => 20,
                    'placeholder' => 'Content here',
                    'class' => 'edit-textarea'
                ]
            ])
            ->add('start_date', DateTimeType::class, [
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
                'html5' => true
            ])
            ->add('end_date', DateTimeType::class, [
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
                'html5' => true

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'validation_groups' => false,
        ]);
    }
}
