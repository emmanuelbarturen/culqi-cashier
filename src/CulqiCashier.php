<?php namespace Emm\CulqiCashier;

use Emm\CulqiCashier\Culqi\Card;
use Emm\CulqiCashier\Culqi\Charge;
use Emm\CulqiCashier\Culqi\Customer;
use Emm\CulqiCashier\Culqi\Plan;
use Emm\CulqiCashier\Culqi\Subscription;

/**
 * Class CulqiCashier
 * @package Emm\CulqiCashier
 */
class CulqiCashier
{

    /**
     * The current currency.
     *
     * @var string
     */
    protected static $currency = 'pen';


    /**
     * @return Customer
     */
    public static function Customer(): Customer
    {
        return new Customer();
    }

    /**
     * This not work
     * @return Card
     */
    public static function Card(): Card
    {
        return new Card();
    }

    /**
     * @return Subscription
     */
    public static function Subscription(): Subscription
    {
        return new Subscription();
    }

    /**
     * @return Charge
     */
    public static function Charge(): Charge
    {
        return new Charge();
    }

    /**
     * @return Plan
     */
    public static function Plan(): Plan
    {
        return new Plan();
    }

    /**
     * @return string
     */
    public static function usesCurrency()
    {
        return env('CULQI_CURRENCY') ?? static::$currency;
    }
}
