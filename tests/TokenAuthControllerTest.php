<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TokenAuthControllerTest extends TestCase
{

    public function __construct()
    {
        // Nós não temos qualquer interesse em testar o Eloquent
        //$this->mock = Mockery::mock('Eloquent', 'User');
    }

    public function tearDown()
    {
        //Mockery::close();
    }

    /**
     * Testing the register method
     *
     * @return void
     */
    public function testRegister()
    {

        //checking if the validators are working
        $response = $this->post('/api/auth/register', ['name' => 'Sally'])
            ->seeJson([
                'message' => 'Validation Failed',
            ]);

        //checking if the validators are working
        $newuser = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'te$te1',
        ];

        $this->post('/api/auth/register', $newuser)
            ->seeJson([
                'message' => 'Validation Failed',
            ]);


        $json = json_decode($this->post('/api/auth/authenticate', $newuser)->response->getContent());
        if( property_exists($json, 'message') && $json->message == 'Invalid Credentials' )
        {
            //registeting the user
            $newuser['password_confirmation'] = 'te$te1';
            $this->post('/api/auth/register', $newuser)
                ->seeJson([
                    "email" => "test@test.com",
                ]);
        }


    }

    /**
     * Testing the authentication method
     *
     * @return void
     */
    public function testAuthenticate()
    {

        $loginuser = ['email' => 'test@test.com'];
        $this->post('/api/auth/authenticate', $loginuser)
            ->seeJson([
                'message' => 'Invalid Credentials',
            ]);

        $this->get('/api/auth/authenticate/user')
            ->seeJson([
                "message" => "Token absent",
        ]);

        $loginuser['password'] = 'te$te1';
        $this->post('/api/auth/authenticate', $loginuser)->seeIsAuthenticated();


        $this->token = json_decode($this->post('/api/auth/authenticate', $loginuser)->response->getContent());
        $this->refreshApplication();
        $this->get('/api/auth/authenticate/user', ['Authorization' => 'Bearer ' . (string)$this->token->token])
        ->seeJson([
            "name" => "test"
        ]);
    }


}
