<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{
    protected $header;

    public function setUp()
    {
        parent::setUp();
        $this->Login();
    }

    public function Login()
    {
        $loginuser = ['email' => 'admin@admin.com', 'password' => '@dmin1'];
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
            $this->get('/api/user/', $this->header)->assertNotNull([]);
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

            //checking if the validators are working
            $response = $this->post('/api/user/', ['name' => 'Sally'], $this->header)
                ->seeJson([
                    'message' => 'Validation Failed',
                ]);

            //checking if the validators are working
            $newuser = [
                'name' => 'manager',
                'email' => 'manager@test.com',
                'password' => 'm@nager1',
            ];

            $this->post('/api/user/', $newuser, $this->header)
                ->seeJson([
                    'message' => 'Validation Failed',
                ]);

            //checking if the user already exist
            $json = json_decode($this->post('/api/auth/authenticate', $newuser)->response->getContent());
            if( property_exists($json, 'message') && $json->message == 'Invalid Credentials' ) {
                //registeting the user
                $newuser['password_confirmation'] = 'm@nager1';
                $newuser['role'] = \App\Repositories\Interfaces\UserRepositoryInterface::ROLE_MANAGER;
                $newuser['calories_expected'] = \App\Repositories\Interfaces\UserRepositoryInterface::DEFAULT_EXPECTED_CALORIES_PERSON;
                $this->post('/api/user', $newuser, $this->header)
                    ->seeJson([
                        "email" => "manager@test.com",
                    ]);
            }

            $this->refreshApplication();

            $newuser['name'] = 'delete';
            $newuser['email'] = 'delete@test.com';
            $newuser['password'] = 'de|ete1';
            $newuser['password_confirmation'] = 'de|ete1';
            //checking if the user already exist
            $json = json_decode($this->post('/api/auth/authenticate', $newuser)->response->getContent());
            if( property_exists($json, 'message') && $json->message == 'Invalid Credentials' ) {
                $this->Login();
                $this->post('/api/user', $newuser, $this->header)
                    ->seeJson([
                        "email" => "delete@test.com",
                    ]);
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
            $user = json_decode($this->get('/api/user/3', $this->header)->response->getContent(), true);
            if( $user )
            {
                $user = [
                    'name' => 'manager update',
                    'calories_expected' => 4321,
                ];

                $this->refreshApplication();

                $this->put('/api/user/'.$user['id'], $user, $this->header)->assertResponseOk();
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
            $user = json_decode($this->get('/api/user/4', $this->header)->response->getContent(), true);

            if( sizeof($user) > 0 )
            {
                $this->delete('/api/user/'.$user['id'], [], $this->header)->assertResponseOk();
            }
        }
    }
}
