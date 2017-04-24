<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Class FloatType
 * @package AppBundle\Form\Type
 *
 * @author Jonathan Baudoin <jonathan@ddf.agency>
 */
class FloatType extends AbstractType
{
    public function getParent()
    {
        return NumberType::class;
    }
}