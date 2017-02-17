<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 16/02/17
 * Time: 13:08
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Menu;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMenuData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $menu = new Menu();
        $week = new \DateTime();
        $week->add(new  \DateInterval('P7D'));

        $menu
            ->setDateStart(new \DateTime())
            ->setDateEnd($week)
            ->setUser($this->getReference('user-admin'))
        ;

        $manager->persist($menu);
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}