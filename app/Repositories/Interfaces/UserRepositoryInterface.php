<?php

namespace App\Repositories\Interfaces;

/**
 * Created by Fernando.
 * Date: 5/6/16
 * Time: 9:29 AM
 */
interface UserRepositoryInterface
{

    const DEFAULT_EXPECTED_CALORIES_PERSON = 2000;
    const ROLE_USER = 1;
    const ROLE_MANAGER = 2;
    const ROLE_ADMIN = 3;
    
    const USER_MENU = [
         [['link'=>'meal', 'title'=>'Meals', 'active'=> false]],
         [['link'=>'meal', 'title'=>'Meals', 'active'=> false], ['link'=>'users', 'title'=>'Users', 'active'=> false]],
         [['link'=>'meal', 'title'=>'Meals', 'active'=> false], ['link'=>'users', 'title'=>'Users', 'active'=> false]]
        ];


    /**
     * Getting errors from laravel model transactions
     */
    public function getErrors();

    /**
     * Getting errors from laravel model transactions
     */
    public function getMessage();
        
    /**
     * Create a new user
     *
     * @param $user
     * @return User
     */
    public function create($user);

    /**
     * Save an user
     *
     * @param $user
     * @return boolean
     */
    public function save($user);


    /**
     * Remove user by id
     *
     * @param $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * List all users
     *
     * @param $id
     * @return boolean
     */
    public function getAll();

    /**
     * Get user by id
     *
     * @param $id
     * @return boolean
     */
    public function getUserById($id);
    
    /**
     * Get deleted user by email
     *
     * @param $email
     * @return boolean
     */
    public function getUserDeletedByEmail($email);

    /**
     * Save the expected calories
     *
     * @param $user
     * @return booleanr
     */
    public function updateCaloriesExpected($user);
}