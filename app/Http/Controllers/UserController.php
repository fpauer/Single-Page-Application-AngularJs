<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\Interfaces\UserRepositoryInterface as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    /**
     * Return the meals list from the authenticated User
     * 
     * @return mixed
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $list = $this->meals->getListMealsByUserId($user);
        return $list;
    }

    /**
     * Return a meal from id
     *
     * @return mixed
     */
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $meal = $this->meals->getMealById($user->id, $id);
        return $meal;
    }
    
    /**
     * Store a new meal for the authenticated User
     *
     * @return mixed
     */
    public function store(Request $request)
    {
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

        return $this->users->create($newuser);
    }

    /**
     * Update a meal
     *
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $userFound = $this->users->getUserById($id);
        
        if($userFound)
        {
            $user = $request->all();
            if( $this->users->save($user) )
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

    /**
     * Destroy a meal
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $meal = $this->meals->getMealById($user->id, $id);

        if($meal){
            $this->meals->delete($meal);
            return  response('Success',200);
        }else{
            return response('Unauthoraized',401);
        }
    }
}
