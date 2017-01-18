<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 22/11/16
 * Time: 13:18
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="recipe_has_ingredients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecipeRepository")
 */
class RecipeHasIngredients implements IngredientInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Recipe
     *
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="ingredients")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id", nullable=false)
     */
    protected $recipe;

    /**
     * @var Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="recipes")
     * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id", nullable=false)
     */
    protected $ingredient;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=false)
     * @Assert\NotBlank()
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="measure_unit", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    protected $measureUnit;

    public function __toString()
    {
        return $this->ingredient->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     *
     * @return $this
     */
    public function setRecipe(Recipe $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return Ingredient
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param Ingredient $ingredient
     *
     * @return $this
     */
    public function setIngredient(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeasureUnit()
    {
        return $this->measureUnit;
    }

    /**
     * @param $measureUnit
     *
     * @return $this
     */
    public function setMeasureUnit($measureUnit)
    {
        $this->measureUnit = $measureUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->ingredient->getName();
    }

}
