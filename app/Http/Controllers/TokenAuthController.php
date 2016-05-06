<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class TokenAuthController extends Controller
{
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
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
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
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TymonJWTAuthExceptionsTokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TymonJWTAuthExceptionsTokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (TymonJWTAuthExceptionsJWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    /*
     * Register will parse the request and create a new User in the database
     * hashing the password as the standard Laravel Authentication require
     */
    public function register(Request $request){

        $validator = Validator::make(Input::all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'        => $validator->errors()
            ]);
        }
        
        $newuser= $request->all();
        $password=Hash::make($request->input('password'));

        $newuser['password'] = $password;

        return User::create($newuser);
    }
}

