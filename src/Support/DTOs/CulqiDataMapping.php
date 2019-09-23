<?php namespace Emm\CulqiCashier\Support\DTOs;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;


/**
 * Class CulqiDataMapping
 * @package Emm\CulqiCashier\Support\DTOs
 */
class CulqiDataMapping
{
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var array
     */
    private $extras;

    /**
     * CulqiDataMapping constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $address
     * @param string $phone
     * @param array $extras
     */
    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $address,
        string $phone,
        array $extras = []
    ) {

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->address = $address;
        $this->phone = $phone;
        $this->extras = $extras;
    }

    /**
     * @return array
     */
    function data(): array
    {
        return array_filter([
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
            "email" => $this->email,
            "address" => $this->address,
            "address_city" => 'Lima',
            "country_code" => 'PE',
            "phone_number" => $this->phone,
        ]);
    }

    /**
     * rules at
     * https://culqi.com/api/#/clientes#create
     * @return array
     */
    function culqiCustomerRules()
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
    }

    /**
     * Check if current user has complete information to be culqi client
     * @return array
     */
    public function validateCulqiCustomerData(): array
    {
        $userAttributes = $this->data();
        $translator = new Translator(new ArrayLoader(), 'es_PE');
        $validatorFactory = new ValidatorFactory($translator);
        $validator = $validatorFactory->make($userAttributes, $this->culqiCustomerRules());
        if ($validator->fails()) {
            return $validator->messages()->messages();
        }
        return [];
    }
}
