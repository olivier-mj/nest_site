<?php

namespace App\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagSearchType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('class');
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => true,
            'search' => '/search',
            'value_Property' => 'id',
            'label_Property' => 'name'
        ]);
    }

    /**

     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (Collection $value): array {
                return $value->map(fn ($d) => (string)$d->getId())->toArray();
            },
            /**
             * @template T
             * @param T[] $ids
             * @return T|null
             */
            function (array $ids) use ($options): Collection {
                if (empty($ids)) {
                    return new ArrayCollection([]);
                }
               $className =($options['class']);
                return new ArrayCollection(
                    /** @phpstan-ignore-next-line */
                    $this->em->getRepository($className)->findBy(['id' => $ids]) 
                );
            }
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['expanded'] = false;
        $view->vars['placeholder'] = 'Ajouter un tag';
        $view->vars['placeholder_in_choices'] = false;
        $view->vars['multiple'] =  true;
        $view->vars['preferred_choices'] = [];
        $view->vars['choices'] = $this->choices($form->getData());
        $view->vars['choice_translation_domain'] = false;
        $view->vars['full_name'] .= '[]';
        $view->vars['attr']['data-remote'] = $options['search'];
        $view->vars['attr']['data-value'] = $options['value_Property'];
        $view->vars['attr']['data-label'] = $options['label_Property'];
    }

    public function getBlockPrefix(): string
    {
        return 'choice';
    }

    private function choices(Collection $value): array
    {
        return $value
            ->map(fn ($d) => new ChoiceView($d, (string)$d->getId(), (string) $d))
            ->toArray();
    }
}
