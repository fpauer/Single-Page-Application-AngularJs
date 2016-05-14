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
        $mealExist = $this->getMealById($meal['user_id'], $meal['id']);

        if($mealExist)
        {
            $mealExist['description'] = $meal['description'];
            $mealExist['calories'] = $meal['calories'];
            $mealExist['consumed_at'] = $meal['consumed_at'];

            return $mealExist->save();
        }
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
    public function getListMealsByUserId($user_id)
    {
        return Meals::where('user_id', '=', $user_id)->orderBy('consumed_at', 'DESC')->get();
    }



    /**
     * Get a list of the  meals by user, dates and time
     *
     * @param $user
     * @return array
     */
    public function getListMealsByUserDates($user_id, $date_from, $date_to, $time_from, $time_to)
    {
        return Meals::where('user_id', $user_id)
            ->whereBetween('consumed_at',array($date_from.' 00:00:00',$date_to.' 23:59:59'))
            ->whereRaw('CAST(consumed_at AS time) >= CAST(\''.$time_from.':00\' AS time)')
            ->whereRaw('CAST(consumed_at AS time) <= CAST(\''.$time_to.':00\' AS time)')
            ->get();
    }
}