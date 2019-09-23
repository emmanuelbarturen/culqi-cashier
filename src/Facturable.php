<?php namespace Emm\CulqiCashier;

use Culqi\Error\InvalidApiKey;
use Emm\CulqiCashier\Support\DTOs\CulqiDataMapping;
use Exception;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;

/**
 * Trait Facturable
 * @package Emm\CulqiCashier
 */
trait Facturable
{

    /**
     * @return CulqiDataMapping
     */
    abstract public function culqiDataMapping(): CulqiDataMapping;


    /**
     * @return mixed
     */
    /*function culqiMappingAttributes(): array
    {
        return [
            "first_name" => $this->names,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "address" => $this->address,
            "address_city" => 'Lima',
            "country_code" => 'PE',
            "phone_number" => $this->phone,
        ];
    }*/

    /**
     * rules at
     * https://culqi.com/api/#/clientes#create
     * @return array
     */
    /* function culqiCustomerRules()
     {
         return [
             "first_name" => 'required|min:2|max:50',
             "last_name" => 'required|min:2|max:50',
             "email" => 'required|email|min:5|max:50',
             "address" => 'required|min:2|max:50',
             "address_city" => 'required|min:2|max:50',
             "country_code" => 'required|size:2',
             "phone_number" => 'required|min:5|max:15',
             "metadata" => 'array',
         ];
     }*/

    /**
     * Check if current user has complete information to be culqi client
     * @return array
     */
    /* public function validateCulqiCustomerData(): array
     {
         $userAttributes = $this->culqiMappingAttributes();
         $translator = new Translator(new ArrayLoader(), 'es_PE');
         $validatorFactory = new ValidatorFactory($translator);
         $validator = $validatorFactory->make($userAttributes, $this->culqiCustomerRules());
         if ($validator->fails()) {
             return $validator->messages()->messages();
         }
         return [];
     }*/

    /**
     * @return \Culqi\create|mixed
     * @throws \Culqi\Error\InvalidApiKey
     * @throws \Exception
     */
    public function createCulqiCustomer()
    {
        if (!array_key_exists('culqi_customer_id', get_object_vars($this)['attributes'])) {
            throw new Exception('No se ha encontrado el campo culqi_customer_id');
        }

        $userDetails = $this->culqiDataMapping()->toArray();
        $userDetails['phone_number'] = str_replace([' '], "", $userDetails['phone_number']);

        $culqiCustomer = CulqiCashier::Customer()->exists($userDetails['email']);
        if ($culqiCustomer === null) {
            $culqiCustomer = CulqiCashier::Customer()->store($userDetails);
        } else {
            $culqiCustomer = CulqiCashier::Customer()->update($culqiCustomer->id, $userDetails);
        }

        $this->culqi_customer_id = $culqiCustomer->id;
        $this->save();

        return 'Culqi Customer Created!';
    }

    /**
     * @return \Culqi\create|mixed|null
     */
    public function culqiAttemptCustomer()
    {
        try {
            if (!$this->culqi_customer_id) {
                $errors = $this->validateCulqiCustomerData();
                if (empty($errors)) {
                    return $this->createCulqiCustomer();
                } else {
                    return $errors;
                }
            }
        } catch (InvalidApiKey $e) {
            logger()->emergency($e->getMessage());
            return null;
        } catch (Exception $ex) {
            logger()->emergency($ex->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Make a "one off" charge on the customer for the given amount.
     *
     * @param float $amount
     * @param string $description
     * @param string $sourceId
     * @param array $data
     * @return \Culqi\create
     */
    public function charge(float $amount, string $description, string $sourceId, array $data)
    {
        $user = $this->culqiDataMapping()->data();
        $options = [
            'currency_code' => $this->preferredCurrencyCode(),
            'description' => $description,
            'email' => $data['email'] ?? $user['email'],
            'amount' => $amount * 100,
            'source_id' => $sourceId,
            'antifraud_details' => $user,
        ];

        return CulqiCashier::Charge()->create($options);
    }

    /**
     * @param $token
     * @return \Culqi\create
     * @throws InvalidApiKey
     */
    public function culqiStoreCreditCard($token)
    {
        return CulqiCashier::Card()->create($this->culqi_customer_id, $token);
    }

    /**
     * Get the Culqi supported currency used by the entity.
     *
     * @return string
     */
    public function preferredCurrencyCode()
    {
        return CulqiCashier::usesCurrency();
    }

}
