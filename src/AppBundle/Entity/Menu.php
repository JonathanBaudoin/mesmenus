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
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\OneToMany(targetEntity="Meal", mappedBy="menu", cascade={"all"})
     */
    protected $meals;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var ShoppingList
     *
     * @ORM\OneToOne(targetEntity="ShoppingList", mappedBy="menu", cascade={"persist"})
     */
    protected $shoppingList;


    public function __construct()
    {
        $this->meals = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getMeals()
    {
        return $this->meals;
    }

    /**
     * @param Meal $meal
     *
     * @return $this
     */
    public function addMeal(Meal $meal)
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
            $meal->setMenu($this);
        }

        return $this;
    }

    /**
     * @param Meal $meal
     *
     * @return $this
     */
    public function removeMeal(Meal $meal)
    {
        if ($this->meals->contains($meal)) {
            $this->meals->removeElement($meal);
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setMealsValues()
    {
        $currentMeals = [];
        $meals        = [];
        $startDate    = clone $this->dateStart;

        /** @var Meal $meal */
        foreach ($this->meals as $meal) {
            $currentMeals[$meal->getDate()->format('d-m-Y').'-'.$meal->getType()] = $meal;
        }

        for ($d = $startDate; $d <= $this->dateEnd; $d->add(new \DateInterval('P1D'))) {
            foreach (Meal::$mealTypes as $mealType) {
                $meals[$d->format('d-m-Y').'-'.$mealType] = [
                    'date' => new \DateTime($d->format('d-m-Y')),
                    'type' => $mealType
                ];
            }
        }

        foreach ($currentMeals as $key => $value) {
            if (!isset($meals[$key])) {
                $this->removeMeal($value);
            }
        }

        foreach ($meals as $key => $value) {
            if (!isset($currentMeals[$key])) {
                $meal = new Meal();
                ($value['type'] === 'lunch') ? $value['date']->setTime(12, 00) : $value['date']->setTime(19, 00);
                $meal
                    ->setDate($value['date'])
                    ->setType($value['type'])
                    ->setMenu($this)
                ;

                $this->addMeal($meal);
            }
        }
    }

    /**
     * @return ShoppingList
     */
    public function getShoppingList()
    {
        return $this->shoppingList;
    }

    /**
     * @param ShoppingList $shoppingList
     *
     * @return $this
     */
    public function setShoppingList($shoppingList)
    {
        $this->shoppingList = $shoppingList;

        return $this;
    }



}
