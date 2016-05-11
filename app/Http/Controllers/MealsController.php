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
     * Return the meals list from the authenticated User
     * 
     * @return mixed
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $list = $this->meals->getListMealsByUserId($user);
        return $list;
    }

    /**
     * Return a meal from id
     *
     * @return mixed
     */
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $meal = $this->meals->getMealById($user->id, $id);
        return $meal;
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
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $mealFound = $this->meals->getMealById($user->id, $id);
        
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
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $meal = $this->meals->getMealById($user->id, $id);

        if($meal){
            $this->meals->delete($meal);
            return  response('Success',200);
        }else{
            return response('Unauthoraized',401);
        }
    }
}