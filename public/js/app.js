/**
 * Created by Fernando on 5/8/16.
 *
 * Meals application 
 * 
 * @param $stateProvider
 * @param $urlRouterProvider
 * @param $authProvider
 * @param $provide
 * @param LoggerProvider
 */

    angular
        .module('app.meals', ['ui.router'
                            , 'satellizer'
                            , 'ui.bootstrap'
                            , 'ui.bootstrap.showErrors'
                            , 'ngAnimate'
                            , 'zingchart-angularjs'
                            , 'app.logger'])
        .config(['$stateProvider', '$urlRouterProvider', '$authProvider', '$provide', 'LoggerProvider', moduleConfig ]);

    function moduleConfig( $stateProvider, $urlRouterProvider, $authProvider, $provide, LoggerProvider) {

        //Production = false
        LoggerProvider.enabled(true);
        
        $authProvider.loginUrl = '/api/auth/authenticate';

        $urlRouterProvider.otherwise('/meal');

        $stateProvider
            .state('meal', {
                url: '/meal',
                templateUrl: '/js/tpl/meal.html',
                controller: 'MealsController'
            })
            .state('login', {
                url: '/login',
                templateUrl: '/js/tpl/login.html',
                controller: 'AuthController'
            })
            .state('register', {
                url: '/register',
                templateUrl: '/js/tpl/register.html',
                controller: 'AuthController'
            })
            .state('users', {
                url: '/users',
                templateUrl: '/js/tpl/users.html',
                controller: 'UsersController'
            })
            .state('settings', {
                url: '/settings',
                templateUrl: '/js/tpl/settings.html',
                controller: 'SettingsController'
            });


    }

/**
 * Running the application
 */
    angular
        .module('app.meals')
        .run(function($http, $auth, $state, $rootScope, $location) {

            $rootScope.checkAuthentication = function() {

                /**
                 * If not authenticated return to login
                 */
                if ($auth.isAuthenticated()) {

                    //refreshing user data
                    function complete(response, status, headers, config) {
                        if (response !== undefined) {
                            $rootScope.currentUser = response.data.user;
                            $rootScope.numberOfCalories = parseInt(response.data.user.calories_expected);
                            $rootScope.menus = response.data.menus;
                        }
                    }
                    function failed(e) {
                        if (e.data && e.data.description) {
                            logger.error('XHR Failed for {0} : {1}', ['refreshing user data', e.data.description]);
                        }
                        $rootScope.currentUser = null;
                        $location.url('/login');
                    }
                    $http.get('api/auth/authenticate/user').then(complete).catch(failed);
                }
                else
                {
                    $rootScope.currentUser = null;
                    $location.url('/login');
                }
            };
            $rootScope.checkAuthentication();

            /**
             * When the state changes check the authentication
             */
            $rootScope.$on('$stateChangeStart', function (event) {
                $rootScope.checkAuthentication();
            });

            /**
             * When the route changes check the authentication
             */
            $rootScope.$on('$routeChangeStart', function (event) {
                $rootScope.checkAuthentication();
            });

            /**
             * Global function to logout
             */
            $rootScope.logout = function() {
                $auth.logout().then(function() {
                    $rootScope.currentUser = null;
                    $state.go('login');
                });
            };
        });

/**
 * Filter to create range values
 */
angular
    .module('app.meals')
    .filter('range', function() {
        return function(input, min, max) {
            min = parseInt(min); //Make string input int
            max = parseInt(max);
            for (var i=min; i<max; i++)
                input.push(i);
            return input;
        };
    });

