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
     * @Assert\GreaterThanOrEqual("today UTC")
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\GreaterThanOrEqual("today UTC")
     * @Assert\Expression(
     *     "this.dateStart() =< value",
     *     message=""
     * )
     */
    protected $dateEnd;


    protected $recipes;


    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

}
