<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 19/01/17
 * Time: 14:51
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="shopping_list")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShoppingListRepository")
 */
class ShoppingList
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
     * @ORM\OneToOne(targetEntity="Menu", inversedBy="shoppingList")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */
    protected $menu;

    /**
     * @var array
     *
     * @ORM\Column(name="additional_items", type="array")
     */
    protected $additionalItems;

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
     * @return array
     */
    public function getAdditionalItems()
    {
        return $this->additionalItems;
    }

    /**
     * @param array $additionalItems
     *
     * @return $this
     */
    public function setAdditionalItems(array $additionalItems = array())
    {
        $this->additionalItems = $additionalItems;

        return $this;
    }

}
