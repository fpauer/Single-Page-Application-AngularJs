/**
 * Created by Fernando on 5/9/16.
 *
 * Settings controller to save the expected user calories per day
 * 
 * @param $http
 * @param $rootScope
 * @param $scope
 * @param Logger
 *
 */
angular
    .module('app.meals')
    .controller('SettingsController', SettingsController);

SettingsController.$inject = ['$http', '$rootScope', '$scope', 'Logger'];

function SettingsController($http, $rootScope, $scope, Logger) {

    var logger = Logger.getInstance('SettingsController');
    $scope.apiPath = '/api/user/' + $rootScope.currentUser.id +'/calories';//api path
    $scope.onlyNumbers = /^\d+$/;//used to format the input number
    $scope.numberOfCalories = $rootScope.numberOfCalories;

    //saving the number of calories expected
    $scope.save = function () {
        $rootScope.numberOfCalories = $scope.numberOfCalories;
        $http.put($scope.apiPath, {'calories_expected': $rootScope.numberOfCalories}).then(complete).catch(failed);

        function complete(response, status, headers, config) {
            $scope.showSuccess(true);
        }

        function failed(e) {
            if (e.data && e.data.description) {
                logger.error('XHR Failed for {0} : {1}', ['read Users', e.data.description]);
            }
        }
    };

    //alerts   ---------------------------------------------------------------------------------------------------------
    $scope.successTextAlert = "Settings saved successfully!";
    $scope.showSuccessAlert = false;
    $scope.showSuccess = function(value) {
        $scope.showSuccessAlert = value;
    };

}