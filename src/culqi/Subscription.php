<?php namespace Emm\CulqiCashier\Culqi;


/**
 * Class Subscription
 * @package Emm\CulqiCashier\Culqi
 */
class Subscription extends ApiResource
{
    /**
     * @param string $cardId
     * @param string $planId
     * @return \Culqi\create
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function create(string $cardId, string $planId)
    {
        $instance = self::_instance();
        return $instance->Subscriptions->create(['card_id' => $cardId, 'plan_id' => $planId]);
    }

    /**
     * @param $planId
     * @return \Culqi\delete
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function cancel($planId)
    {
        $instance = self::_instance();
        return $instance->Subscriptions->delete($planId);
    }

}
