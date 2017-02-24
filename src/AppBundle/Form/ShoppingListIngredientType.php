<?php

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            ->add('amount', IntegerType::class, [
                'label'    => 'amount',
                'required' => true,
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