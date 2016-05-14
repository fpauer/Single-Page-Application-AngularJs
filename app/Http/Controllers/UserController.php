<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
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
        return $this->users->getAll();
    }


    /**
     * Return a user from id
     *
     * @return mixed
     */
    public function show($id)
    {
        return $this->users->getUserById($id);
    }

    /**
     * Store a new meal for the authenticated User
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $newuser = Input::all();
        $created = $this->users->create($newuser);
        if( $created )
        {
            return $created;
        }
        else {
            return response()->json([
                'message' => $this->users->getMessage(),
                'errors' => $this->users->getErrors()
            ], 500);
        }
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

            $userExists = $this->users->getUserDeletedByEmail($user['email']);
            if($userExists->id == $user['id'])
            {
                if( $this->users->save($user) )
                {
                    return  response('Success',200);
                }
                else
                {
                    return response('Not saved',500);
                }
            }
            else
            {
                return response()->json([
                    'message'   => 'Validation Failed',
                    'errors'        => ['email' => ["The email has already been taken."]]
                ]);
            }
        }else{
            return response('Unauthoraized',401);
        }
    }

    /**
     * Destroy a user
     *
     * @return mixed
     */
    public function destroy($id)
    {
        if( $this->users->deleteById($id))
        {
            return  response('Success',200);
        }
        else
        {
            return response('Not deleted',500);
        }
    }
}
