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
     */
    public function create(array $options)
    {
        try {
            $instance = self::_instance();
            return $instance->Charges->create($options);
        } catch (Exception $e) {
            logger($e);
            return null;
        }
    }
}
