<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 03/11/16
 * Time: 13:14
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="ingredient")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IngredientRepository")
 */
class Ingredient implements IngredientInterface
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RecipeHasIngredients", mappedBy="recipe")
     */
    protected $recipes;


    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = ucfirst(strtolower($name));

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
     * @param RecipeHasIngredients $recipe
     *
     * @return $this
     */
    public function addRecipe(RecipeHasIngredients $recipe)
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setIngredient($this);
        }

        return $this;
    }

    /**
     * @param RecipeHasIngredients $recipe
     *
     * @return $this
     */
    public function removeRecipe(RecipeHasIngredients $recipe)
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            $recipe->setIngredient(null);
        }

        return $this;
    }
}