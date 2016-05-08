<?php

namespace App\Repositories\Interfaces;

/**
 * Created by Fernando.
 * Date: 5/6/16
 * Time: 9:29 AM
 */
interface UserRepositoryInterface
{

    /**
     * Create a new user
     *
     * @param $user
     * @return User
     */
    public function create($user);
    
    /**
     * Remove user by email
     *
     * @param $email
     * @return boolean
     */
    public function deleteUserByEmail($email);

}