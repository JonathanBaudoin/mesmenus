<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 19/01/17
 * Time: 14:51
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="shopping_list_ingredients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShoppingListRepository")
 */
class ShoppingListIngredients
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
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="shoppingListIngredients")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $menu;

    /**
     * @var Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient")
     * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id")
     */
    protected $ingredient;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredient_name", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    protected $ingredientName;

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

    /**
     * To know if this product is in the Menu or not
     * @var boolean
     *
     * @ORM\Column(name="extra_menu", type="integer", nullable=false)
     * @Assert\NotNull()
     * @Assert\Choice({0, 1})
     */
    protected $extraMenu = false;

    /**
     * To know if the product is in the customer cart (if he ever bought)
     *
     * @var integer
     *
     * @ORM\Column(name="in_cart", type="integer", nullable=true)
     * @Assert\NotNull()
     * @Assert\Choice({0, 1})
     */
    protected $inCart = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     *
     * @return $this
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

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
     * @Assert\NotNull(message="shoppingListIngredient.name.not_null")
     *
     * @return string|null
     */
    public function getIngredientName()
    {
        if (!empty($this->ingredientName)) {
            return $this->ingredientName;
        } elseif (!empty($this->ingredient)) {
            return $this->ingredient->getName();
        }

        return null;
    }

    /**
     * @param string $ingredientName
     *
     * @return $this
     */
    public function setIngredientName($ingredientName = null)
    {
        if (!is_null($ingredientName)) {
            $this->ingredientName = $ingredientName;
        } else {
            if (!is_null($this->getIngredient())) {
                $this->ingredientName = $this->ingredient->getName();
            }
        }

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
     * @param string $measureUnit
     *
     * @return $this
     */
    public function setMeasureUnit($measureUnit)
    {
        $this->measureUnit = $measureUnit;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExtraMenu()
    {
        return $this->extraMenu;
    }

    /**
     * @param bool $extraMenu
     *
     * @return $this
     */
    public function setExtraMenu($extraMenu)
    {
        $this->extraMenu = $extraMenu;
        return $this;
    }

    /**
     * @return int
     */
    public function isInCart()
    {
        return $this->inCart;
    }

    /**
     * @param int $inCart
     *
     * @return $this
     */
    public function setInCart($inCart)
    {
        $this->inCart = $inCart;
        return $this;
    }

}
