<?php

/**
 * Created by PhpStorm.
 * User: fpauer
 * Date: 5/6/16
 * Time: 9:31 AM
 */

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;

class UserRepository implements UserRepositoryInterface
{
    protected $errors = array();

    /**
     * Getting errors from laravel model transactions
     */
    public function getErrors()
    {
        return $this->errors;
    }
    /**
     * Remove user by email
     *
     * @param $email
     * @return boolean
     */
    public function deleteUserByEmail($email)
    {
        return User::where('email', '=', $email)->delete() > 0;
    }

    /**
     * Create a new user
     *
     * @param $user
     * @return User
     */
    public function create($user)
    {
        return User::create($user);
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

        if($userExist)
        {
            $userExist['name'] = $user['name'];
            $userExist['email'] = $user['email'];
            $userExist['role'] = $user['role'];
            $userExist['calories_expected'] = $user['calories_expected'];

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
    public function getUserById($id)
    {
        return User::where('id', '=', $id)->get()->first();
    }
    
    /**
     * Remove user by email
     *
     * @param $email
     * @return boolean
     */
    public function getUserByEmail($email)
    {
        return User::where('email', '=', $email)->get()->first();
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
            $errors[] = $e->getMessage();   // insert query
            return false;
        }
    }
}