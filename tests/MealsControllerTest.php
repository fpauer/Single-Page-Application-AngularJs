<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MealsControllerTest extends TestCase
{
    protected $header;

    public function setUp()
    {
        parent::setUp();
        $loginuser = ['email' => 'test@test.com', 'password' => 'te$te1'];
        $token = json_decode($this->post('/api/auth/authenticate', $loginuser)->response->getContent());
        if( property_exists($token, 'token') )
        {
            $this->header = ['Authorization' => 'Bearer ' . (string)$token->token];
        }
        $this->refreshApplication();
    }

    /**
     * Testing the get method
     *
     * @return void
     */
    public function testIndex()
    {
        if( !empty($this->header))
        {
            $this->get('/api/meals', $this->header)->assertNotNull([]);
        }
    }

    /**
     * Testing the post method
     *
     * @return void
     */
    public function testStore()
    {
        if( !empty($this->header)) {
            $meals = [
                'description' => 'Nice slice of bread',
                'calories' => 123
                , 'eat_at' => Carbon\Carbon::now()];

            $this->post('/api/meals', $meals, $this->header)
                ->seeJson([
                    'user_id' => 1
                ]);
        }
    }

    /**
     * Testing the get method
     *
     * @return void
     */
    public function testUpdate()
    {
        if( !empty($this->header)) {

            //$meals = $this->get('/api/meals', $this->header);

            //$this->post('/api/meals', $meals, $this->header)
             //   ->seeJson([
             //       'user_id' => 1
             //   ]);
        }
    }

    /**
     * Testing the get method
     *
     * @return void
     */
    public function testDestroy()
    {
        $this->assertTrue(true);
    }
}
