<?php


namespace App\Form\Type;


use App\Form\DataTransformer\TagArrayToStringTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TagsInputType extends AbstractType
{
    private  TagRepository $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new TagArrayToStringTransformer($this->tags), true);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['tags'] = $this->tags->findAll();
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }
}
