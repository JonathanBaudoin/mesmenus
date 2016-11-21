<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 02/11/16
 * Time: 13:35
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="recipe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecipeRepository")
 */
class Recipe
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    protected $slug;

    /**
     * @var int
     *
     * @ORM\Column(name="preparation_time", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    protected $preparationTime;

    /**
     * @var int
     *
     * @ORM\Column(name="cooking_time", type="integer")
     * @Assert\Type("integer")
     */
    protected $cookingTime;

    /**
     * @var int
     *
     * @ORM\Column(name="break_time", type="integer")
     * @Assert\Type("integer")
     */
    protected $breakTime;

    /**
     * @var int
     *
     * @ORM\Column(name="eaters", type="integer")
     * @Assert\Type("integer")
     */
    protected $eaters;

    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="text")
     */
    protected $instructions;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return int
     */
    public function getPreparationTime()
    {
        return $this->preparationTime;
    }

    /**
     * @param int $preparationTime
     *
     * @return $this
     */
    public function setPreparationTime($preparationTime)
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getCookingTime()
    {
        return $this->cookingTime;
    }

    /**
     * @param int $cookingTime
     *
     * @return $this
     */
    public function setCookingTime($cookingTime)
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getBreakTime()
    {
        return $this->breakTime;
    }

    /**
     * @param int $breakTime
     *
     * @return $this
     */
    public function setBreakTime($breakTime)
    {
        $this->breakTime = $breakTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getEaters()
    {
        return $this->eaters;
    }

    /**
     * @param int $eaters
     *
     * @return $this
     */
    public function setEaters($eaters)
    {
        $this->eaters = $eaters;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param string $instructions
     *
     * @return $this
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }
}
