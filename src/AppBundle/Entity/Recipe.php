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

}
