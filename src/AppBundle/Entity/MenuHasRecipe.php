<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/01/17
 * Time: 14:00
 */

namespace AppBundle\Entity;

/**
 * @ORM\Table(name="menu_has_recipe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class MenuHasRecipe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $menu;

    protected $recipe;

    protected $meal;
}