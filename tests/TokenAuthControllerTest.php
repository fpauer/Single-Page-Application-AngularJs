<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TokenAuthControllerTest extends TestCase
{
    /**
     * Testing the authentication method 
     *
     * @return void
     */
    public function testAuthenticate()
    {
        //$response = $this->call('GET', '/');
        //$this->assertEquals(200, $response->status());

        //$this->post('/user', ['name' => 'Sally'])
        //    ->seeJson([
        //        'created' => true,
        //    ]);
    }
    
    /**
     * Testing the register method
     *
     * @return void
     */
    public function testRegister()
    {

        //checking if the validators are working
        $this->post('/api/register', ['name' => 'Sally'])
            ->seeJson([
                'message' => 'Validation Failed',
            ]);

        //checking if the validators are working
        $newuser = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'te$te',
        ];
        
        $this->post('/api/register', $newuser)
            ->seeJson([
                'message' => 'Validation Failed',
            ]);

    }
}
