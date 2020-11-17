<?php namespace Emm\CulqiCashier\Culqi;


/**
 * Class Plan
 * @package Emm\CulqiCashier\Culqi
 */
class Plan extends ApiResource
{
    /**
     * @param int[] $options
     * @return \Culqi\all
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function all($options = ['limit' => 10])
    {
        $instance = self::_instance();
        return $instance->Plans->all($options);
    }
}
