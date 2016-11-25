<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 21/11/16
 * Time: 13:22
 */

namespace AppBundle\Form;


use AppBundle\Repository\IngredientRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'recipe.form.name.label',
                'required' => true,
            ])
            ->add('preparationTime', NumberType::class, [
                'label' => 'recipe.form.preparationTime.label',
                'required' => true,
                'attr' => ['placeholder' => 'recipe.form.minutes.placeholder'],

            ])
            ->add('cookingTime', NumberType::class, [
                'label' => 'recipe.form.cookingTime.label',
                'required' => false,
                'attr' => ['placeholder' => 'recipe.form.minutes.placeholder'],

            ])
            ->add('breakTime', NumberType::class, [
                'label' => 'recipe.form.breakTime.label',
                'required' => false,
                'attr' => ['placeholder' => 'recipe.form.minutes.placeholder'],

            ])
            ->add('eaters', NumberType::class, [
                'label' => 'recipe.form.eaters.label',
                'required' => true,
            ])
            ->add('ingredients', EntityType::class, [
                'label' => 'Ingrédients',
                'class' => 'AppBundle\Entity\Ingredient',
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function (IngredientRepository $ingredientRepository) {
                    return $ingredientRepository->findAllQueryBuilder();
                },
            ])
            ->add('instructions', TextareaType::class, [
                'label' => 'recipe.form.instructions.label',
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Recipe',
        ]);
    }

}
