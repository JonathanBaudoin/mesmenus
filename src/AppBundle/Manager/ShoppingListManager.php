<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeHasIngredients;
use AppBundle\Entity\ShoppingListIngredients;

class ShoppingListManager extends BaseManager
{
    public function generateShoppingListFromRecipesMenu(Menu $menu)
    {
        $shoppingList = [];
        /** @var Meal $meal */
        foreach ($menu->getMeals() as $meal) {

            /** @var Recipe $recipe */
            foreach ($meal->getRecipes() as $recipe) {

                /** @var RecipeHasIngredients $ingredient */
                foreach ($recipe->getIngredients() as $ingredient) {
                    $ingredientId                              = $ingredient->getIngredient()->getId();
                    $shoppingList[$ingredientId]['ingredient'] = $ingredient->getIngredient();
                    $shoppingList[$ingredientId]['name']       = $ingredient->getName();

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

    public function saveShoppingListFromRecipesMenu(Menu $menu)
    {
        // Delete shoppingList item which come from Meals Menu
        if (!empty($menu->getShoppingListIngredients())) {
            /** @var ShoppingListIngredients $item */
            foreach ($menu->getShoppingListIngredients() as $item) {
                if (!$item->isExtraMenu()) {
                    $this->em->remove($item);
                }
            }
            $this->em->flush();
        }

        $shoppingList = $this->generateShoppingListFromRecipesMenu($menu);

        if (!empty($shoppingList)) {
            foreach ($shoppingList as $key => $values) {
                foreach ($values['values'] as $quantityKey => $quantityValue) {
                    $ingredient = new ShoppingListIngredients();
                    $ingredient
                        ->setMenu($menu)
                        ->setIngredient($values['ingredient'])
                        ->setAmount($quantityValue)
                        ->setMeasureUnit($quantityKey)
                        ->setExtraMenu(false)
                    ;
                    $menu->addShoppingListIngredient($ingredient);

                }
            }
            $this->em->persist($menu);
            $this->em->flush();
        }
    }
}
