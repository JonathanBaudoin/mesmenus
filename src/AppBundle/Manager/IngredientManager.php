<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 25/11/16
 * Time: 13:19
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Ingredient;

class IngredientManager extends BaseManager
{
    /**
     * @param Ingredient $ingredient
     *
     * @return null|Ingredient
     */
    public function ingredientAlreadyExists(Ingredient $ingredient)
    {
        return $this->getRepository()->findOneBy(['name' => $ingredient->getName()]);
    }
}
