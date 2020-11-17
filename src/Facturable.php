<?php namespace Emm\CulqiCashier;

use Culqi\Error\InvalidApiKey;
use Emm\CulqiCashier\Events\CulqiCharged;
use Emm\CulqiCashier\Events\CulqiCustomerCreated;
use Emm\CulqiCashier\Events\CulqiCustomerSubscribed;
use Emm\CulqiCashier\Events\CulqiCustomerUpdated;
use Exception;

/**
 * Trait Facturable
 * @package Emm\CulqiCashier
 */
trait Facturable
{

    /**
     * @var string
     */
    protected $culqiCustomerField = 'culqi_customer_id';


    /**
     * @return array
     */
    abstract public function culqiAntiFraud(): array;

    /**
     * @return array
     */
    private function getCustomerData(): array
    {
        return array_filter($this->culqiAntiFraud());
    }

    /**
     * @param array $base
     * @param array $new
     * @return array
     */
    private function replace(array $base, array $new)
    {
        foreach ($new as $key => $val) {
            if (isset($base[$key])) {
                $base[$key] = $val;
            }
        }
        return $base;
    }

    /**
     * @param array $antifraud
     * @return \Culqi\create|mixed
     * @throws InvalidApiKey
     * @throws Exception
     */
    public function createCulqiCustomer(array $antifraud = [])
    {
        if (!array_key_exists($this->culqiCustomerField, get_object_vars($this)['attributes'])) {
            throw new Exception('No se ha encontrado el campo ' . $this->culqiCustomerField);
        }

        $customerData = $this->replace($this->getCustomerData(), $antifraud);
        $customerData['phone_number'] = str_replace([' '], "", $customerData['phone_number']);

        $culqiCustomer = CulqiCashier::Customer()->exists($customerData['email']);
        if ($culqiCustomer === null) {
            $culqiCustomer = CulqiCashier::Customer()->store($customerData);
        } else {
            $culqiCustomer = CulqiCashier::Customer()->update($culqiCustomer->id, $customerData);
        }

        $this->{$this->culqiCustomerField} = $culqiCustomer->id;
        if ($this->save()) {
            event(new CulqiCustomerCreated($culqiCustomer));
            return $culqiCustomer;
        }
        return null;
    }

    /**
     * @param array $details
     * @return \Culqi\update
     * @throws InvalidApiKey
     */
    public function updateCulqiCustomer(array $details)
    {
        $customerData = $this->getCustomerData();
        $updatedData = $this->replace($customerData, $details);
        $culqiCustomer = CulqiCashier::Customer()->exists($customerData['email']);
        $updated = CulqiCashier::Customer()->update($culqiCustomer->id, $updatedData);
        event(new CulqiCustomerUpdated($updated));
        return $updated;
    }

    /**
     * Make a "one off" charge on the customer for the given amount.
     *
     * @param float $amount
     * @param string $description
     * @param string $sourceId
     * @param array $antifraud
     * @return \Culqi\create
     * @throws InvalidApiKey
     */
    public function charge(float $amount, string $description, string $sourceId, array $antifraud = [])
    {
        $user = $this->getCustomerData();
        $updatedData = $this->replace($user, $antifraud);
        $options = [
            'currency_code' => CulqiCashier::usesCurrency(),
            'description' => $description,
            'email' => $data['email'] ?? $updatedData['email'],
            'amount' => $amount * 100,
            'source_id' => $sourceId,
            'antifraud_details' => $updatedData,
        ];

        $created = CulqiCashier::Charge()->create($options);
        event(new CulqiCharged($created));
        return $created;
    }

    /**
     * @param $token
     * @return \Culqi\create
     * @throws InvalidApiKey
     */
    public function culqiStoreCreditCard($token)
    {
        return CulqiCashier::Card()->create($this->{$this->culqiCustomerField}, $token);
    }

    /**
     * @param string $planId
     * @param string $sourceId
     * @return \Culqi\create
     * @throws InvalidApiKey
     * @throws Exception
     */
    public function culqiSubscription(string $planId, string $sourceId)
    {
        if (!array_key_exists('culqi_customer_id', get_object_vars($this)['attributes'])) {
            throw new Exception('No se ha encontrado el campo culqi_customer_id');
        }

        if (is_null($this->{$this->culqiCustomerField})) {
            $this->createCulqiCustomer();
        }

        $card = CulqiCashier::Card()->create($this->{$this->culqiCustomerField}, $sourceId);
        $suscription = CulqiCashier::Subscription()->create($card->id, $planId);
        event(new CulqiCustomerSubscribed($suscription));
        return $suscription;
    }

    /**
     * @param string $subscriptionId
     * @return \Culqi\delete
     * @throws InvalidApiKey
     */
    public function culqiUnsubscription(string $subscriptionId)
    {
        $cancel = CulqiCashier::Subscription()->cancel($subscriptionId);
        event(new CulqiCustomerSubscribed($cancel));
        return $cancel;
    }
}
