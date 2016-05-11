<?php

namespace App\Repositories\Interfaces;

/**
 * Created by Fernando.
 * Date: 5/6/16
 * Time: 9:29 AM
 */
interface UserRepositoryInterface
{

    const ROLE_USER = 1;
    const ROLE_MANAGER = 2;
    const ROLE_ADMIN = 3;
    
    const USER_MENU = [
         [['link'=>'meal', 'title'=>'Meals', 'active'=> false]],
         [['link'=>'meal', 'title'=>'Meals', 'active'=> false], ['link'=>'users', 'title'=>'Users', 'active'=> false]],
         [['link'=>'meal', 'title'=>'Meals', 'active'=> false], ['link'=>'users', 'title'=>'Users', 'active'=> false]]
        ];
    
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

    /**
     * Remove user by email
     *
     * @param $email
     * @return boolean
     */
    public function getUserByEmail($email);

}