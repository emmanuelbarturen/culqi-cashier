<?php namespace Emm\CulqiCashier\Culqi;

use Exception;


/**
 * Class Charge
 * @package Emm\CulqiCashier\Culqi
 */
class Charge extends ApiResource
{

    /**
     * @param array $options
     * @return \Culqi\create|null
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function create(array $options)
    {
        $instance = self::_instance();
        return $instance->Charges->create($options);
    }
}
