/**
 * Created by fpauer on 5/9/16.
 */

angular
    .module('app.meals')
    .controller('SettingsController', SettingsController);

function SettingsController($state, $http, $rootScope, $scope, $auth) {

    $scope.onlyNumbers = /^\d+$/;
    $scope.successTextAlert = "Settings saved successfully!";
    $scope.showSuccessAlert = false;
    $scope.numberOfCalories = $rootScope.numberOfCalories;

    // switch flag
    $scope.showSuccess = function(value) {
        $scope.showSuccessAlert = value;
    };

    $scope.save = function () {
        $rootScope.numberOfCalories = $scope.numberOfCalories;
        $http.put('/api/user/' + $rootScope.currentUser.id +"/calories", {'calories_expected': $rootScope.numberOfCalories})
            .success(function (data)
            {
                $scope.showSuccess(true);
            });
    };
}