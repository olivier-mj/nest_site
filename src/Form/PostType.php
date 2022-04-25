<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Form\Type\SwitchType;
use App\Form\Type\TagsInputType;
use DateTime;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    protected UrlGeneratorInterface $UrlGeneratorInterface;

    public function __construct(UrlGeneratorInterface $UrlGeneratorInterface)
    {
        $this->UrlGeneratorInterface = $UrlGeneratorInterface;
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'autofocus' => true,
                    'placeholder' => 'Title here',
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'rows' => 20,
                    'placeholder' => 'Content here',
                    'class' => 'edit-textarea form-control'
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                //                'format' => 'dd/MM/yyyy',
                'html5' => true,
                'attr' => [
                    'class' => 'datepicker form-control',
                ]
            ])
            ->add('online', SwitchType::class, [
                'required' => false,
                'label' => 'En ligne',

            ])

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'image_uri' => true,
                'label' => 'Choose file',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Chose a category',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            /* ->add('tags', TagsInputType::class, [
                // 'label' => 'label.tags',
                "label" => false,
                'required' => false,
                'attr' => [
                    'data-toggle' => 'tagsinput',
                    'data-role' => "tagsinput",
                    'placeholder' => 'Chose a tag',

                ]
            ]) */
            ->add('tags', TagSearchType::class, [
                'class' => Tag::class,
                'required' => false,
                'search' => $this->UrlGeneratorInterface->generate('api_tags'),
                'label_Property' =>'name',
                'value_Property' => 'id'
            ]);

        // $builder->get('createdAt')->addModelTransformer(new CallbackTransformer(
        //     function ($value) {
        //         if (!$value) {
        //             return new DateTime('now');
        //         }
        //         return $value;
        //     },
        //     function ($value) {
        //         return $value;
        //     }
        // ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Post::class]);
    }
}
