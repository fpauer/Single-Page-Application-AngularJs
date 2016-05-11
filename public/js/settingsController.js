/**
 * Created by fpauer on 5/9/16.
 */

angular
    .module('app.meals')
    .controller('SettingsController', SettingsController);

function SettingsController($state, $http, $rootScope, $scope, $auth) {

    $scope.numberOfCalories = 0;
    $scope.successTextAlert = "Settings saved successfully!";
    $scope.showSuccessAlert = false;

    // switch flag
    $scope.showSuccess = function(value) {
        $scope.showSuccessAlert = value;
    };

    $scope.init = function () {
        $scope.numberOfCalories =  $rootScope.numberOfCalories;
    };

    $scope.save = function () {
        $rootScope.numberOfCalories = $scope.numberOfCalories;
        $scope.showSuccess(true);
        //$http.post('/api/meals', $scope.newMeal).success(function (data) {
        //    $scope.meals.push(data);
        //    $scope.newMeal = {};
        //});
    };

    $scope.init();
}