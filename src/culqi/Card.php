<?php namespace Emm\CulqiCashier\Culqi;

use Culqi\Culqi;
use Emm\CulqiCashier\CulqiCashier;


/**
 * Class Card
 * @package Emm\CulqiCashier\Culqi
 */
class Card extends ApiResource
{

    /**
     * @param $customerId
     * @param $tokenId
     * @return \Culqi\create
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function create($customerId, $tokenId)
    {
        $instance = self::_instance();
        return $instance->Cards->create(['customer_id' => $customerId, 'token_id' => $tokenId]);
    }

    /**
     * @param null $customerId
     * @param bool $onlyActives
     * @return \Illuminate\Support\Collection|static
     */
    public function get($customerId = null, $onlyActives = true)
    {
        if ($customerId == null) {
            return collect([]);
        }

        $client = CulqiCashier::Customer()->find($customerId);
        if ($client == null || empty($client->cards)) {
            return collect([]);
        }

        $toArray = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($client->cards), true);

        return collect($toArray)->filter(function ($value) use ($onlyActives) {
            if ($onlyActives) {
                return $value['active'];
            }
            return true;
        });
    }

    /**
     * @param $customerId
     * @param $cardNumber
     * @return null|String
     */
    public function findByNumber($customerId, $cardNumber)
    {
        $cards = $this->get($customerId);
        $found = $cards->where('source.card_number', $cardNumber)->first();
        return $found ? $found['id'] : null;
    }

    /**
     * @param $cardId
     * @return \Culqi\delete
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function delete($cardId)
    {
        $instance = self::_instance();
        return $instance->Cards->delete($cardId);
    }
}
