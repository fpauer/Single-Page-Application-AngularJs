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
}