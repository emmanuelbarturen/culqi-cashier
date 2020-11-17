<?php namespace Emm\Tests;


use PHPUnit\Framework\TestCase;

/**
 * Class CustomerTest
 * @package Emm\Tests
 */
class CustomerTest extends TestCase
{
    protected $API_KEY;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $this->API_KEY = getenv("API_KEY");
        //$this->culqi = new Culqi(array("api_key" => $this->API_KEY ));
        parent::setUp();
    }


    /**
     * @test
     */
    public function create_new_customer()
    {

        $this->assertTrue(getenv('APP_ENV') == 'teeeee');
    }
}
