<?php namespace Emm\CulqiCashier\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;


/**
 * Class CulqiCustomerUpdated
 * @package Emm\CulqiCashier\Events
 */
class CulqiCustomerUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * @var
     */
    private $culqiResponse;

    /**
     * CulqiCustomerCreated constructor.
     * @param $culqiResponse
     */
    public function __construct($culqiResponse)
    {
        $this->culqiResponse = $culqiResponse;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->culqiResponse;
    }
}