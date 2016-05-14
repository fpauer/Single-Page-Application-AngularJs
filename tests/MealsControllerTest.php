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
            $data = json_decode($this->get('/api/auth/authenticate/user', $this->header)->response->getContent());
            $this->get('/api/user/'.$data->user->id.'/meals', $this->header)->assertNotNull([]);
        }
    }


    /**
     * Testing the get method
     *
     * @return void
     */
    public function testIndexByDates()
    {
        if( !empty($this->header))
        {
            $data = json_decode($this->get('/api/auth/authenticate/user', $this->header)->response->getContent());
            $this->get('/api/user/'.$data->user->id.'/meals/2016-05-10/2016-05-13/14:00/16:00', $this->header)->assertNotNull([]);
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
            $data = json_decode($this->get('/api/auth/authenticate/user', $this->header)->response->getContent());

            $meal = json_decode($this->get('/api/user/'.$data->user->id.'/meals/2', $this->header)->response->getContent(), true);
            if( sizeof($meal) == 0 )
            {
                $meals = [
                    'description' => 'Nice slice of bread 2',
                    'calories' => 123
                    , 'consumed_at' => Carbon\Carbon::now()];

                $this->post('/api/user/'.$data->user->id.'/meals', $meals, $this->header)
                    ->seeJson([
                        'user_id' => $data->user->id
                    ]);

                $meal = json_decode($this->get('/api/user/'.$data->user->id.'/meals/3', $this->header)->response->getContent(), true);
                if( sizeof($meal) == 0 )
                {
                    $this->refreshApplication();
                    $meals = [
                        'description' => 'Nice slice of bread 3',
                        'calories' => 1234
                        , 'consumed_at' => Carbon\Carbon::now()];

                    $this->post('/api/user/'.$data->user->id.'/meals', $meals, $this->header)
                        ->seeJson([
                            'user_id' => $data->user->id
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
            $data = json_decode($this->get('/api/auth/authenticate/user', $this->header)->response->getContent());

            $meal = json_decode($this->get('/api/user/'.$data->user->id.'/meals/3', $this->header)->response->getContent(), true);
            if( $meal )
            {
                $meal['description'] = 'Updated Nice slice of bread 2';
                $meal['calories'] = 1234;
                $meal['consumed_at'] = Carbon\Carbon::now();

                $this->refreshApplication();

                $this->put('/api/user/'.$data->user->id.'/meals/'.$meal['id'], $meal, $this->header)->assertResponseOk();
            }
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
            $data = json_decode($this->get('/api/auth/authenticate/user', $this->header)->response->getContent());

            $meal = json_decode($this->get('/api/user/'.$data->user->id.'/meals/2', $this->header)->response->getContent(), true);

            if( sizeof($meal) > 0 )
            {
                $this->delete('/api/user/'.$data->user->id.'/meals/'.$meal['id'], [], $this->header)->assertResponseOk();
            }
        }
    }


    /**
     * Testing Unauthorized actions
     *
     * @return void
     */
    public function testMealsUnauthorized()
    {
        if( !empty($this->header)) {
            $data = json_decode($this->get('/api/auth/authenticate/user', $this->header)->response->getContent());

            $this->get('/api/user/1/meals/1', $this->header)->seeStatusCode(401);
            
            //$meal = json_decode($this->get('/api/user/'.$data->user->id.'/meals/1', $this->header)->response->getContent(), true);

            //if( sizeof($meal) > 0 )
            //{
            //    $this->delete('/api/user/'.$data->user->id.'/meals/'.$meal['id'], [], $this->header)->assertResponseOk();
            //}
        }
    }
}
