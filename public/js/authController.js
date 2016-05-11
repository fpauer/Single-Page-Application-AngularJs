/**
 * Created by fpauer on 5/8/16.
 */

       
    angular
        .module('app.meals')
        .controller('AuthController', AuthController);
    
    function AuthController($auth, $state, $http, $rootScope, $scope) {
    
        $scope.email = '';
        $scope.password = '';
        $scope.newUser = {};
        $scope.loginError = false;
        $scope.loginErrorText = '';
    
        $scope.login = function () {
            $scope.$broadcast('show-errors-check-validity');
    
            if ($scope.loginForm.$valid) {
    
                var credentials = {
                    email: $scope.email,
                    password: $scope.password
                }
    
                $auth.login(credentials).then(function () {
                    $rootScope.$broadcast('$routeChangeStart');
                    $scope.loginError = false;
                    $scope.loginErrorText = '';
                    $state.go('meal');
                }, function (error) {
                    $scope.loginError = true;
                    $scope.loginErrorText = error.data.message;
    
                });
            }
    
        };
    
        $scope.register = function () {
    
            $scope.$broadcast('show-errors-check-validity');
            if ($scope.loginForm.$valid) {
                $http.post('/api/auth/register', $scope.newUser)
                    .then(registerComplete)
                    .catch(registerFailed);
            }
    
    
            function registerComplete(response, status, headers, config) {

                if (response.data.errors === undefined) {
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
    
            function registerFailed(e) {
                var newMessage = 'XHR Failed for getCustomer'
                if (e.data && e.data.description) {
                    newMessage = newMessage + '\n' + e.data.description;
                }
                e.data.description = newMessage;
                logger.error(newMessage);
                return $q.reject(e);
            }
    
        };
    
    }
    