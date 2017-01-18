<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/01/17
 * Time: 13:28
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class Menu
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\GreaterThanOrEqual("today", message="menu.dateStart.gte.today")
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\GreaterThanOrEqual("today", message="menu.dateEnd.gte.today")
     * @Assert\Expression(
     *     "this.getDateStart() <= value",
     *     message="menu.dateEnd.gte.dateStart"
     * )
     */
    protected $dateEnd;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MenuHasRecipes", mappedBy="menu", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $recipes;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


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
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     *
     * @return $this
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     *
     * @return $this
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

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
     * @param ArrayCollection $recipes
     *
     * @return $this
     */
    public function setRecipes($recipes)
    {
        $this->recipes = $recipes;

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

}
