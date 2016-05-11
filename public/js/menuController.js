/**
 * Created by fpauer on 5/9/16.
 */


angular
    .module('app.meals')
    .controller('MenuController', MenuController);

function MenuController($state, $http, $rootScope, $scope, $auth) {
    
    $scope.activeMenu = 'meal';
    
}