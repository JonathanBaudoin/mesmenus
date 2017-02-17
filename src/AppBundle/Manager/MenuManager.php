<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 27/01/17
 * Time: 13:37
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeHasIngredients;

class MenuManager extends BaseManager
{
    public function getShoppingList(Menu $menu)
    {
        $shoppingList = [];
        /** @var Meal $meal */
        foreach ($menu->getMeals() as $meal) {

            /** @var Recipe $recipe */
            foreach ($meal->getRecipes() as $recipe) {

                /** @var RecipeHasIngredients $ingredient */
                foreach ($recipe->getIngredients() as $ingredient) {

                    $ingredientId                        = $ingredient->getIngredient()->getId();
                    $shoppingList[$ingredientId]['name'] = $ingredient->getName();

                    if (!isset($shoppingList[$ingredientId]['values'][$ingredient->getMeasureUnit()])) {
                        $shoppingList[$ingredientId]['values'][$ingredient->getMeasureUnit()] = $ingredient->getAmount();
                    } else {
                        $shoppingList[$ingredientId]['values'][$ingredient->getMeasureUnit()] = $shoppingList[$ingredientId]['values'][$ingredient->getMeasureUnit()] + $ingredient->getAmount();
                    }
                }
            }
        }

        return $shoppingList;
    }
}
