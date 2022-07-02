<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\TagSearchType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                // 'attr' => [
                //     'class' => 'h-80',
                // ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                //                'format' => 'dd/MM/yyyy',
                // 'html5' => true,
                // 'attr' => [
                //     "value" => (new  \DateTime())->format('Y-m-d H:i')
                // ]
            ])
            ->add('updatedAt', DateTimeType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text',
                //                'format' => 'dd/MM/yyyy',
                'html5' => true,
                // 'attr' => [
                //     "value" => (new  \DateTime())->format('Y-m-d H:i')
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
                'label_Property' => 'name',
                'value_Property' => 'id'
            ])
            // ->add('user')
            // ->add('submit', SubmitType::class,[
            //     'attr' => [
            //         'class' => 'inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 
            //         border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none
            //         focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
