<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Repositories\Interfaces\UserRepositoryInterface as User;

class TokenAuthController extends Controller
{

    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    /*
     * Authentication method : It takes the email and password from the request
     * and try to generate a token for the given user credential. If an error in
     * the process occur an exception is raised and the method return the appropriate response.
     *
     * @param Request $request
     * @return json
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message'   => 'Invalid Credentials','errors' => ['invalid_credentials']] , 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message'   => 'Could not create a token','errors' => ['could_not_create_token']], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    /*
     * Parsing the token in the request and if the token is valid
     * and the user is present it return the user itself,
     * otherwise also in this case an exception is raised
     *
     * @return json
     */
    public function getAuthenticatedUser(Request $request)
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'User not found','errors' => ['user_not_found']], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['message'   => 'Token expired', 'errors' => ['token_expired']], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['message'   => 'Token invalid', 'errors' => ['token_invalid']], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['message'   => 'Token absent', 'errors' => ['token_absent']], $e->getStatusCode());

        }

        $user_settings = 
            [
                'user' => $user,
                'menus' => UserRepositoryInterface::USER_MENU[$user['role']]
            ];
        
        return response()->json($user_settings);
    }

    /*
     * Register will parse the request and create a new User in the database
     * hashing the password as the standard Laravel Authentication require
     */
    public function register(Request $request){

        //checking if the fields are right
        $validator = Validator::make(Input::all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'same:password',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'        => $validator->errors()
            ]);
        }

        $newuser = Input::all();
        $password = Hash::make(Input::get('password'));

        $newuser['password'] = $password;
        $newuser['role'] = UserRepositoryInterface::ROLE_USER;
        $newuser['calories_expected'] = UserRepositoryInterface::DEFAULT_EXPECTED_CALORIES_PERSON;
        
        return $this->users->create($newuser);
    }

    /**
     * Updating the user expected calories
     *
     * @param $caloris
     */
    public function updateCalories(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if($user) {
            $data = Input::all();

            $user['calories_expected'] = $data['calories_expected'];

            if ( $this->users->updateCaloriesExpected($user) )
            {
                return  response('Success',200);
            }
            else
            {
                return response('Not saved',500);
            }
        }else{
            return response('Unauthoraized',401);
        }
    }
}

