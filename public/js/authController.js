/**
 *
 * Created by Fernando on 5/8/16.
 *
 * Authentication Controller used to deal with login and user registration
 *
 *
 * @param $auth
 * @param $state
 * @param $http
 * @param $rootScope
 * @param $scope
 * @param Logger
 */
    angular
        .module('app.meals')
        .controller('AuthController', AuthController);

    AuthController.$inject = ['$auth', '$state', '$http', '$rootScope', '$scope', 'Logger'];

    function AuthController($auth, $state, $http, $rootScope, $scope, Logger) {

        var logger = Logger.getInstance('AuthController');
        $scope.apiPath = '/api/auth';
        $scope.email = '';
        $scope.password = '';
        $scope.newUser = {};
        $scope.loginError = false;
        $scope.loginErrorText = '';

        $scope.login = function () {

            //checking if there are errors in the login form
            $scope.$broadcast('show-errors-check-validity');
            if ($scope.loginForm.$valid) {

                //setting the credentials to log in
                var credentials = {
                    email: $scope.email,
                    password: $scope.password
                }
    
                $auth.login(credentials).then(function (response) { //SUCCESS
                    $scope.loginError = false;
                    $scope.loginErrorText = '';

                    //getting the user data
                    function complete(response, status, headers, config) {
                        if (response !== undefined) {
                            $rootScope.currentUser = response.data.user;
                            $rootScope.numberOfCalories = parseInt(response.data.user.calories_expected);
                            $rootScope.menus = response.data.menus;
                            $state.go('meal');
                        }
                    }

                    //if failed return to login
                    function failed(e) {
                        if (e.data && e.data.description) {
                            logger.error('XHR Failed for {0} : {1}', ['getting user data', e.data.description]);
                        }
                        $rootScope.currentUser = null;
                        $location.url('/login');
                    }
                    $http.get($scope.apiPath+'/authenticate/user').then(complete).catch(failed);

                }, function (e) { //ERROR
                    if (e.data && e.data.description) {
                        logger.error('XHR Failed for {0} : {1}', ['login', e.data.description]);
                    }
                    $scope.loginError = true;
                    $scope.loginErrorText = e.data.message;

                });
            }
    
        };
    
        $scope.register = function () {

            //checking if there are errors in the register form
            $scope.$broadcast('show-errors-check-validity');
            if ($scope.loginForm.$valid) {
                $http.post($scope.apiPath+'/register', $scope.newUser)
                    .then(registerComplete)
                    .catch(registerFailed);
            }
    
    
            function registerComplete(response, status, headers, config) {//SUCCESS

                if (response.data.errors === undefined) {//if no errors found, log in
                    $scope.email = $scope.newUser.email;
                    $scope.password = $scope.newUser.password;
                    $scope.login();
                } else {
                    $scope.registerError = true;
                    if(response.data.errors.password !== undefined)
                        $scope.registerErrors = response.data.errors.password;
                    if(response.data.errors.email !== undefined)
                        $scope.registerErrors = response.data.errors.email;
                }
            }
    
            function registerFailed(e) { //ERROR
                if (e.data && e.data.description) {
                    logger.error('XHR Failed for {0} : {1}', ['register', e.data.description]);
                }
            }
    
        };
    
    }
    