<?php

namespace AppBundle\Form;


use AppBundle\Form\Type\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingListIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'label'    => 'product',
                'class'    => 'AppBundle\Entity\Ingredient',
                'multiple' => false,
                'required' => false,
            ])
            ->add('ingredientName', TextType::class, [
                'label'    => 'product.name',
                'required' => false,
            ])
            ->add('amount', FloatType::class, [
                'label'    => 'amount',
                'required' => true,
                'attr'     => [
                    'min'  => 0.5,
                    'step' => 0.5
                ]
            ])
            ->add('measureUnit', TextType::class, [
                'label'    => 'measureUnit',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ShoppingListIngredients',
        ]);
    }
}