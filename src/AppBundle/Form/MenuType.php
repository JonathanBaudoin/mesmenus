<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/01/17
 * Time: 13:56
 */

namespace AppBundle\Form;


use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuType extends AbstractType
{
    /** @var Registry */
    protected $doctrine;

    /** @var User|null */
    protected $user;

    /**
     * MenuType constructor.
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->user     = $tokenStorage->getToken()->getUser();
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Menu $menu */
        $menu = $builder->getData();

        $dateFormat = 'dd/MM/yy';

        $builder
            ->add('dateStart', DateType::class, array(
                'label'    => 'menu.form.dateStart',
                'required' => true,
                'widget'   => 'single_text',
                'format'   => $dateFormat,
                'attr'     => [
                    'data-date-format' => $dateFormat
                ]
            ))
            ->add('dateEnd', DateType::class, array(
                'label'    => 'menu.form.dateEnd',
                'required' => true,
                'widget'   => 'single_text',
                'format'   => $dateFormat,
                'attr'     => [
                    'data-date-format' => $dateFormat
                ]
            ))
        ;

        if (!is_null($menu->getId())) {
            $startDate = clone $menu->getDateStart();
            $recipes   = $this->doctrine->getRepository('AppBundle:Recipe')->findAllQueryBuilder($this->user)->getQuery()->getResult();

            for ($d = $startDate; $d <= $menu->getDateEnd(); $d->add(new \DateInterval('P1D'))) {
                foreach (Meal::$mealTypes as $meal) {
                    $builder
                        ->add('meal_'.$d->format('Y-m-d').'_'.$meal, EntityType::class, array(
                            'class'       => 'AppBundle\Entity\Recipe',
                            'required'    => false,
                            'multiple'    => true,
                            'mapped'      => false,
                            'placeholder' => 'menu.form.recipes.empty_value',
                            'choices'     => $recipes,
                        ))
                    ;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Menu',
        ]);
    }
}
