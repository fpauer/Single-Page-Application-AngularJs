/**
 * Created by Fernando on 5/9/16.
 *
 * Menu controller
 *
 * @param $state
 * @param $http
 * @param $rootScope
 * @param $scope
 * @param $auth
 * @constructor
 */


angular
    .module('app.meals')
    .controller('MenuController', MenuController);

function MenuController($state, $http, $rootScope, $scope, $auth) {
    
    $scope.activeMenu = 'meal';
    
}