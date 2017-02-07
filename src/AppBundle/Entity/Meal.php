<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 18/01/17
 * Time: 16:07
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="meal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MealRepository")
 */
class Meal
{
    public static $mealTypes = ['lunch', 'dinner'];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=6, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=6)
     */
    protected $type;

    /**
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="meals")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     */
    protected $menu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_day", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    protected $date;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Recipe")
     * @ORM\JoinTable(name="meal_has_recipes",
     *      joinColumns={@ORM\JoinColumn(name="meal_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="recipe_id", referencedColumnName="id")}
     *  )
     */
    protected $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
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
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    /**
     * @param Recipe $recipe
     *
     * @return $this
     */
    public function addRecipe(Recipe $recipe)
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
        }

        return $this;
    }

    /**
     * @param Recipe $recipe
     *
     * @return $this
     */
    public function removeRecipe(Recipe $recipe)
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
        }

        return $this;
    }

    /**
     * @param ArrayCollection $recipes
     *
     * @return $this
     */
    public function setRecipes(ArrayCollection $recipes)
    {
        foreach ($recipes as $recipe) {
            $this->addRecipe($recipe);
        }

        foreach ($this->getRecipes() as $recipe) {
            if (!in_array($recipe, $recipes->toArray())) {
                $this->removeRecipe($recipe);
            }
        }

        return $this;
    }
}
