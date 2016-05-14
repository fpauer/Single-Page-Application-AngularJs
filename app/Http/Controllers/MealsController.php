<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\Interfaces\MealsRepositoryInterface as Meals;
use Tymon\JWTAuth\Facades\JWTAuth;

class MealsController extends Controller
{
    protected $meals;

    public function __construct(Meals $meals)
    {
        $this->meals = $meals;
    }

    /**
     * Return the meals list
     * 
     * @return mixed
     */
    public function index($user_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $this->meals->getListMealsByUserId($user_id);
    }

    /**
     * Return the meals list between dates and time
     *
     * @return mixed
     */
    public function indexByDates($user_id, $date_from, $date_to, $time_from, $time_to)
    {
        $user = JWTAuth::parseToken()->authenticate();
    
        return $this->meals->getListMealsByUserDates($user_id, $date_from, $date_to, $time_from, $time_to);
    }


    /**
     * Return a meal from id
     *
     * @return mixed
     */
    public function show($user_id, $meal_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $this->meals->getMealById($user->id, $meal_id);
    }
    
    /**
     * Store a new meal for the authenticated User
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $newMeal = $request->all();
        $newMeal['user_id']=$user->id;
        return  $this->meals->create($newMeal);
    }

    /**
     * Update a meal
     *
     * @return mixed
     */
    public function update(Request $request, $user_id, $meal_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $mealFound = $this->meals->getMealById($user->id, $meal_id);

        if($mealFound)
        {
            $meal = $request->all();
            if( $this->meals->save($meal) )
            {
                return  response('Success',200);
            }
            else
            {
                return response('Not saved',500);
            }
        }else{
            return response('Unauthoraized',401);
        }
    }

    /**
     * Destroy a meal
     *
     * @return mixed
     */
    public function destroy($user_id, $meal_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $meal = $this->meals->getMealById($user->id, $meal_id);

        if($meal){
            $this->meals->delete($meal);
            return  response('Success',200);
        }else{
            return response('Unauthoraized',401);
        }
    }
}
