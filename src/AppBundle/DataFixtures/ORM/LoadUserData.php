<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 14/02/17
 * Time: 14:43
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Admin
        $userAdmin = new User();
        $userAdmin
            ->setUsername('admin')
            ->setPlainPassword('admin')
            ->setEmail('admin@admin.fr')
            ->addRole('ROLE_SUPER_ADMIN')
            ->setEnabled(true)
        ;

        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference('user-admin', $userAdmin);

        // User 1
        $user = new User();
        $user
            ->setUsername('user1')
            ->setPlainPassword('user1')
            ->setEmail('user1@user1.fr')
            ->addRole('ROLE_USER')
            ->setEnabled(true)
        ;

        $manager->persist($user);
        $manager->flush();
        $this->addReference('user-user1', $user);

        // User 2
        $user = new User();
        $user
            ->setUsername('user2')
            ->setPlainPassword('user2')
            ->setEmail('user2@user2.fr')
            ->addRole('ROLE_USER')
            ->setEnabled(true)
        ;

        $manager->persist($user);
        $manager->flush();
        $this->addReference('user-user2', $user);

    }

    public function getOrder()
    {
        return 1;
    }
}
