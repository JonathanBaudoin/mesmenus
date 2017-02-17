<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 14/02/17
 * Time: 14:56
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Ingredient;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIngredientData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ingredients = [
            'tomates', 'pommes de terre', 'poireaux', 'navets', 'courgettes', 'panais', 'carottes', 'potiron', 'salade',
            'pommes', 'bananes', 'poires', 'fruits de la passion', 'ananas',
            'spaghetti', 'riz', 'semoule', 'lentilles',
            'blanc de poulet', 'merguez', 'steack haché',
            'coulis de tomates', 'lait de coco',
            'pain à hamburger'
        ];

        foreach ($ingredients as $ingredientName) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredientName);

            $manager->persist($ingredient);
            $manager->flush();

            $this->addReference('ingredient-'.$ingredient->getSlug(), $ingredient);
        }
    }

    public function getOrder()
    {
        return 2;
    }
}
