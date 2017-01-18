<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/01/17
 * Time: 14:00
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu_has_recipes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class MenuHasRecipes
{
    public static $meals = ['lunch', 'dinner'];

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
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="recipes")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     */
    protected $menu;

    /**
     * @var Recipe
     *
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="menus")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id", nullable=false)
     */
    protected $recipe;

    /**
     * @var string
     *
     * @ORM\Column(name="meal", length=9, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=9)
     */
    protected $meal;


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
     * @return Recipe
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
    public function setRecipe($recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param string $meal
     *
     * @return $this
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;

        return $this;
    }
}
