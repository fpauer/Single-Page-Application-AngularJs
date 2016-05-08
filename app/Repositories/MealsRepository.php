<?php
/**
 * Created by Fernando.
 * Date: 5/7/16
 * Time: 10:27 AM
 */

namespace App\Repositories;


use App\Models\Meals;
use App\Repositories\Interfaces\MealsRepositoryInterface;

class MealsRepository implements MealsRepositoryInterface
{
    /**
     * Create a new meal
     *
     * @param $meal
     * @return Meal
     */
    public function create($meal)
    {
        return Meals::create($meal);
    }

    /**
     * Save a meal
     *
     * @param $meal
     * @return boolean
     */
    public function save($meal)
    {
        $meal = $this->getMealById($meal['user_id'], $meal['id']);

        if($meal) return $meal->save();
        return false;
    }

    /**
     * Delete a meal
     *
     * @param $meal
     * @return rows affected
     */
    public function delete($meal)
    {
        $meal = $this->getMealById($meal['user_id'], $meal['id']);

        if( $meal ) return $meal->delete();
        else return 0;
    }


    /**
     * Get a meal by Id
     *
     * @param $id
     * @param $id
     * @return Meal
     */
    public function getMealById($user_id, $id)
    {
        return Meals::where('user_id','=',$user_id)->where('id','=',$id)->get()->first();
    }

    /**
     * Get a list of the user's meals
     *
     * @param $user
     * @return array
     */
    public function getListMealsByUserId($user)
    {
        return Meals::where('user_id', $user->id)->get();
    }
}