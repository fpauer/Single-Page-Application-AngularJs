<?php

namespace App\Repositories\Interfaces;

/**
 * Created by Fernando.
 * Date: 5/6/16
 * Time: 9:29 AM
 */
interface MealsRepositoryInterface
{
    /**
     * Create a new meal
     *
     * @param $meal
     * @return Meal
     */
    public function create($meal);
    
    /**
     * Save a meal
     *
     * @param $meal
     * @return boolean
     */
    public function save($meal);
        
    /**
     * Delete a meal
     *
     * @param $meal
     * @return rows affected
     */
    public function delete($meal);


    /**
     * Get a meal by Id
     *
     * @param $id
     * @param $id
     * @return Meal
     */
    public function getMealById($user_id, $id);


    /**
     * Get a list of the user's meals
     *
     * @param $user
     * @return array
     */
    public function getListMealsByUserId($user);


    /**
     * Get a list of the  meals by user, dates and time
     *
     * @param $user
     * @return array
     */
    public function getListMealsByUserDates($user, $date_from, $date_to, $time_from, $time_to);

}