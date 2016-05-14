<?php

/**
 * Author: Fernando Dias
 * Date: 5/6/16
 * Time: 9:31 AM
 */

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRepository implements UserRepositoryInterface
{

    const DEFAULT_EXPECTED_CALORIES_PERSON = 2000;
    const ROLE_USER = 1;
    const ROLE_MANAGER = 2;
    const ROLE_ADMIN = 3;

    static $MEAL_PERMISSION = ['link'=>'meal', 'title'=>'Meals', 'controller'=> 'App\Http\Controllers\MealsController'];
    static $USER_PERMISSION = ['link'=>'users', 'title'=>'Users', 'controller'=> 'App\Http\Controllers\UserController'];
    static $AUTH_PERMISSION = ['link'=>'', 'title'=>'', 'controller'=> 'App\Http\Controllers\TokenAuthController'];
    
    protected $message = "";
    protected $errors = array();

    public static function getUserPermissions($role)
    {
        if(UserRepository::ROLE_USER==$role)//user
        {
            return [UserRepository::$MEAL_PERMISSION, UserRepository::$AUTH_PERMISSION];
        }
        else//manager, admin
        {
            return [UserRepository::$MEAL_PERMISSION, UserRepository::$USER_PERMISSION, UserRepository::$AUTH_PERMISSION];
            
        }
    }

    /**
     * Getting errors from laravel model transactions
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Getting errors from laravel model transactions
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Create a new user
     *
     * @param $user
     * @return User
     */
    public function create($newuser)
    {
        //checking if the fields are right
        $validator = Validator::make($newuser, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'same:password',
        ]);

        //validating the data sent 
        if ($validator->fails())
        {
            $this->message ='Validation Failed';
            $this->errors = $validator->errors();
            return false;
        }

        $password = Hash::make($newuser['password']);//creating the password
        $newuser['password'] = $password;
        $newuser['calories_expected'] = UserRepository::DEFAULT_EXPECTED_CALORIES_PERSON;//defining a default value for calories

        //sometimes the role could come as array or value
        if(isset($newuser['role']) && isset($newuser['role']['id']))
        {
            $newuser['role'] = $newuser['role']['id'];
        }


        //checking if the user already exist even deleted
        $userExists = $this->getUserDeletedByEmail($newuser['email']);
        if($userExists)//if exist restore and uptade the data
        {
            $userExists->restore();
            $newuser['id'] = $userExists->id;

            if( $this->save($newuser) )//save the user
            {
                return $this->getUserById($newuser['id']);
            }
            else
            {
                $this->message ='Not saved';
                $this->errors = ['user', ['User not found']];
                return false;
            }
        }
        else
        {
            return User::create($newuser);
        }
    }

    /**
     * Save an user
     *
     * @param $user
     * @return boolean
     */
    public function save($user)
    {
        $userExist = $this->getUserById($user['id']);

        if($userExist)//if exists
        {
            if(isset($user['name'])) $userExist['name'] = $user['name'];
            if(isset($user['email']) )$userExist['email'] = $user['email'];
            
            //sometimes the role could come as array or value
            if(isset($user['role']))
            {
                if(isset($user['role']['id']))
                {
                    $userExist['role'] = $user['role']['id'];
                }
                else
                {
                    $userExist['role'] = $user['role'];
                }
            }
            if(isset($user['calories_expected'])) $userExist['calories_expected'] = $user['calories_expected'];

            return $userExist->save();
        }
        return false;
    }

    /**
     * Remove user by id
     *
     * @param $id
     * @return boolean
     */
    public function deleteById($id)
    {
        $user = User::where('id','=',$id)->get()->first();
        return $user->delete();
    }
    
    /**
     * List all users
     *
     * @param $id
     * @return boolean
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * List user by id
     *
     * @param $id
     * @return boolean
     */
    public function getUserById($id)
    {
        return User::where('id','=',$id)->get()->first();
    }
    
    /**
     * Get deleted user by email
     *
     * @param $email
     * @return boolean
     */
    public function getUserDeletedByEmail($email)
    {
        return User::withTrashed()->where('email', '=', $email)->get()->first();
    }

    /**
     * Save the expected calories
     *
     * @param $user
     * @return boolean
     */
    public function updateCaloriesExpected($user)
    {
        try{
            $user->save();
            return true;
        }
        catch(Exception $e){
            $this->message ='Not saved';
            $this->errors = ['calories_expected', ['Not saved']];
            return false;
        }
    }
}