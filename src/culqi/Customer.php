<?php namespace Emm\CulqiCashier\Culqi;


/**
 * Class Customer
 * @package Emm\CulqiCashier\Culqi
 */
class Customer extends ApiResource
{

    /**
     * @return \Illuminate\Support\Collection
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function list()
    {
        $instance = self::_instance();
        $rs = $instance->Customers->all();
        return collect($rs->data);
    }

    /**
     * @param $email
     * @return mixed
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function exists(string $email)
    {
        $clients = $this->list();
        return $clients->where('email', $email)->first();
    }

    /**
     * @param array $attributes
     * @return \Culqi\create
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function store(array $attributes)
    {
        $instance = self::_instance();
        return $instance->Customers->create($attributes);
    }

    /**
     * @param string $customerId
     * @param array $attributes
     * @return \Culqi\update
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function update(string $customerId, array $attributes)
    {
        $instance = self::_instance();
        return $instance->Customers->update($customerId, $attributes);
    }

    /**
     * @param $customerId
     * @return \Culqi\get|null
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function find(string $customerId)
    {
        $instance = self::_instance();
        return $instance->Customers->get($customerId);
    }

    /**
     * @param string $customerId
     * @return \Culqi\delete
     * @throws \Culqi\Error\InvalidApiKey
     */
    public function delete(string $customerId)
    {
        $instance = self::_instance();
        return $instance->Customers->delete($customerId);
    }
}
