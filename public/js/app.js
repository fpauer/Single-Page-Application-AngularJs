/**
 * Created by fpauer on 5/8/16.
 */

    angular
        .module('app.meals', ['ui.router'
                            , 'satellizer'
                            , 'ui.bootstrap'
                            , 'ui.bootstrap.showErrors'
                            , 'ngAnimate'
                            , 'zingchart-angularjs'])
        .config( moduleConfig );


    function moduleConfig( $stateProvider, $urlRouterProvider, $authProvider, $provide) {

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

    angular
        .module('app.meals')
        .run(function($http, $auth, $state, $rootScope, $location) {

            $rootScope.checkAuthentication = function() {
                if ($auth.isAuthenticated()) {
                    $http.get('api/auth/authenticate/user')
                        .then(function (response) {
                            if (response !== undefined) {
                                $rootScope.currentUser = response.data.user;
                                $rootScope.numberOfCalories = parseInt(response.data.user.calories_expected);
                                $rootScope.menus = response.data.menus;
                            }
                        })
                        .catch(function (error) {
                            $rootScope.currentUser = null;
                            $location.url('/login');
                        });
                }
                else
                {
                    $rootScope.currentUser = null;
                    $location.url('/login');
                }
            };
            $rootScope.checkAuthentication();

            $rootScope.$on('$stateChangeStart', function (event) {
                $rootScope.checkAuthentication();
            });

            $rootScope.$on('$routeChangeStart', function (event) {
                $rootScope.checkAuthentication();
            });

            $rootScope.logout = function() {
                $auth.logout().then(function() {
                    $rootScope.currentUser = null;
                    $state.go('login');
                });
            };
        });

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

