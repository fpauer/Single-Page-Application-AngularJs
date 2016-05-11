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

            $meal = json_decode($this->get('/api/meals/1', $this->header)->response->getContent(), true);
            if( sizeof($meal) == 0 )
            {
                $meals = [
                    'description' => 'Nice slice of bread',
                    'calories' => 123
                    , 'consumed_at' => Carbon\Carbon::now()];

                $this->post('/api/meals', $meals, $this->header)
                    ->seeJson([
                        'user_id' => 1
                    ]);


                $meal = json_decode($this->get('/api/meals/2', $this->header)->response->getContent(), true);
                if( sizeof($meal) == 0 )
                {
                    $this->refreshApplication();
                    $meals = [
                        'description' => 'Nice slice of bread 2',
                        'calories' => 1234
                        , 'consumed_at' => Carbon\Carbon::now()];

                    $this->post('/api/meals', $meals, $this->header)
                        ->seeJson([
                            'user_id' => 1
                        ]);
                }
            }
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

            $meal = json_decode($this->get('/api/meals/1', $this->header)->response->getContent(), true);
            $meal['description'] = 'Updated Nice slice of bread 1';
            $meal['calories'] = 1234;
            $meal['consumed_at'] = Carbon\Carbon::now();

            $this->refreshApplication();
            $this->put('/api/meals/'.$meal['id'], $meal, $this->header)->assertResponseOk();
        }
    }

    /**
     * Testing the get method
     *
     * @return void
     */
    public function testDestroy()
    {
        if( !empty($this->header)) {
            $meal = json_decode($this->get('/api/meals/1', $this->header)->response->getContent(), true);

            if( sizeof($meal) > 0 )
            {
                $this->delete('/api/meals/'.$meal['id'], [], $this->header)->assertResponseOk();
            }
        }
    }
}
