<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = JWTAuth::parseToken()->authenticate();

        //checking if user has permission to access the controller or the resource
        $action = $request->route()->getAction();
        if( isset($action['controller']) )
        {
            $controller = explode('@',$action['controller']);//getting controller and action
            $permissions = implode(',', array_map(function ($entry) {//reading the permission based on the roles
                return $entry['controller'];
            }, UserRepository::getUserPermissions($user['role'])));

            //checking permissions in the controller
            if (strpos($permissions, $controller[0]) !== false) 
            {

                //checking permissions in the resource
                if( UserRepository::$MEAL_PERMISSION['controller']==$controller[0] )
                {
                    //if not admin and not accessing its own resources, Unauthorized!
                    if( UserRepository::ROLE_ADMIN != $user['role'] && $request->user_id != $user['id'] )
                    {
                        return response()->json([
                            'message'   => 'Unauthorized resource',
                            'errors'        => ['user' => ["You do not have access to this resource."]]
                        ], 401);
                    }
                }
                
            }
            else // Unauthorized access to the controller 
            {
                return response()->json([
                    'message'   => 'Unauthorized access',
                    'errors'        => ['user' => ["You do not have access to this resource."]]
                ], 401);
            }
        }

        return $next($request);
    }
}
