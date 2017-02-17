<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 14/02/17
 * Time: 14:56
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeHasIngredients;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRecipeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $recipes = [
            'Spaghetti à la bolognaise' => [
                'user'            => $this->getReference('user-admin'),
                'preparationTime' => 20,
                'cookingTime'     => 20,
                'eaters'          => 4,
                'instructions'    => 'Lorem ipsum dolor sit amet...',
                'ingredients'     => [
                    'ingredient-spaghetti' => [
                        'amount'      => 500,
                        'measureUnit' => 'g'
                    ],
                    'ingredient-tomates' => [
                        'amount'      => 4,
                        'measureUnit' => ''
                    ],
                ]
            ],
            'Hamburger' => [
                'user'            => $this->getReference('user-admin'),
                'preparationTime' => 15,
                'cookingTime'     => 8,
                'eaters'          => 4,
                'instructions'    => 'Lorem ipsum dolor sit amet...',
                'ingredients'     => [
                    'ingredient-pain-a-hamburger' => [
                        'amount'      => 4,
                        'measureUnit' => ''
                    ],
                    'ingredient-tomates' => [
                        'amount'      => 4,
                        'measureUnit' => ''
                    ],
                    'ingredient-salade' => [
                        'amount'      => 0.5,
                        'measureUnit' => ''
                    ],
                    'ingredient-steack-hache' => [
                        'amount'      => 4,
                        'measureUnit' => ''
                    ],
                ]
            ],
            'Couscous' => [
                'user'            => $this->getReference('user-admin'),
                'preparationTime' => 30,
                'cookingTime'     => 20,
                'eaters'          => 6,
                'instructions'    => 'Lorem ipsum dolor sit amet...',
                'ingredients'     => [
                    'ingredient-semoule' => [
                        'amount'      => 600,
                        'measureUnit' => 'g'
                    ],
                    'ingredient-merguez' => [
                        'amount'      => 12,
                        'measureUnit' => ''
                    ],
                    'ingredient-courgettes' => [
                        'amount'      => 3,
                        'measureUnit' => ''
                    ],
                    'ingredient-navets' => [
                        'amount'      => 6,
                        'measureUnit' => ''
                    ],
                ]
            ],
            'Purée' => [
                'user'            => $this->getReference('user-user1'),
                'preparationTime' => 10,
                'cookingTime'     => 5,
                'eaters'          => 4,
                'instructions'    => 'Lorem ipsum dolor sit amet...',
                'ingredients'     => [
                    'ingredient-pommes-de-terre' => [
                        'amount'      => 8,
                        'measureUnit' => ''
                    ],
                ]
            ],
        ];

        foreach ($recipes as $recipeName => $recipeValues) {
            $recipe = new Recipe();
            $recipe->setName($recipeName);

            foreach ($recipeValues as $valueKey => $valueValue) {
                if (!is_array($valueValue)) {
                    $method = 'set'.ucfirst($valueKey);
                    $recipe->$method($valueValue);
                } else {
                    if ($valueKey === 'ingredients') {
                        foreach ($valueValue as $ingredientName => $ingredientValues) {
                            $ingredient = new RecipeHasIngredients();
                            $ingredient->setRecipe($recipe);
                            $ingredient
                                ->setIngredient($this->getReference($ingredientName))
                                ->setAmount($ingredientValues['amount'])
                                ->setMeasureUnit($ingredientValues['measureUnit'])
                            ;
                            $recipe->addIngredient($ingredient);
                        }
                    }
                }
            }

            $manager->persist($recipe);
            $manager->flush();

            $this->addReference('recipe-'.$recipe->getSlug(), $recipe);
        }
    }

    public function getOrder()
    {
        return 3;
    }
}
