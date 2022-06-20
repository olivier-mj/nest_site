<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\TagSearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BlogType extends AbstractType
{

    public function __construct(private UrlGeneratorInterface $UrlGeneratorInterface)
    {
        $this->UrlGeneratorInterface = $UrlGeneratorInterface;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                "label" => false,
                "attr" => ["placeholder" => "Title post"]
            ])
            ->add('content', null, [
                "label" => false,
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                //                'format' => 'dd/MM/yyyy',
                // 'html5' => true,
                // 'attr' => [
                //     'class' => 'datepicker',
                // ]
            ])
            ->add('online', null, [
                "label" => false,
  
            ])
            ->add('imageFile', DropzoneType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Drag and drop or browse',
                    'data-controller' => 'mydropzone',

                ],
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Chose a category',
                'class' => Category::class,
                'choice_label' => 'name',
                // 'attr' => [
                //     'class' => 'form-control'
                // ]
            ])
            ->add('tags', TagSearchType::class, [
                'class' => Tag::class,
                'required' => false,
                'label' => false,
                'search' => $this->UrlGeneratorInterface->generate('admin.api_tags'),
                'label_Property' =>'name',
                'value_Property' => 'id'
            ]);
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
