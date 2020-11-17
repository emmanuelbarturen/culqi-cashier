<?php namespace Emm\CulqiCashier\Culqi;

use Culqi\Culqi;


/**
 * Class ApiResource
 * @package Emm\CulqiCashier\Culqi
 */
abstract class ApiResource
{
    /**
     * The Culqi API key.
     *
     * @var string
     */
    protected static $culqiKey;

    /**
     * @return Culqi
     * @throws \Culqi\Error\InvalidApiKey
     */
    protected static function _instance()
    {
        return new Culqi(["api_key" => self::getCulqiKey()]);
    }

    /**
     * Get the Culqi API key.
     *
     * @return string
     */
    public static function getCulqiKey()
    {
        if (static::$culqiKey) {
            return static::$culqiKey;
        }

        if ($key = env('CULQI_SECRET')) {
            return $key;
        }

        return config('services.culqi.secret');
    }
}
