/**
 * Created by Fernando on 5/9/16.
 *
 * REVISED
 * 
 */
angular
    .module('app.meals')
    .controller('UsersController', UsersController);

UsersController.$inject = ['$http', '$scope', '$timeout', 'modalService', 'Logger'];

function UsersController($http, $scope, $timeout, modalService, Logger) {

    var logger = Logger.getInstance('UsersController');
    $scope.apiPath = '/api/user/';//api path
    $scope.action = "New";
    $scope.availableRoles = [
        {id: '1', name: 'User'},
        {id: '2', name: 'Manager'},
        {id: '3', name: 'Admin'}
    ];
    $scope.users = [];

    //cleaning the forms
    $scope.clean = function (index) {
        $scope.$broadcast('show-errors-reset');//reseting the directive show-errors
        $scope.showError(false);
        $scope.action = "New";
        $scope.newUser = {
            'id': 0,
            'name': '',
            'email': '',
            'password': '',
            'password_confirmation': '',
            'role':{id: '1', name: 'User'}
        };

    };

    //coping values to form to edit the value
    $scope.edit = function (index) {
        $scope.action = "Edit";
        $scope.newUser['id'] = $scope.users[index].id;
        $scope.newUser['name'] = $scope.users[index].name;
        $scope.newUser['email'] = $scope.users[index].email;
        $scope.newUser['role'] = $scope.availableRoles[$scope.users[index].role-1];
    };

    $scope.init = function () {
        $scope.clean();
        $http.get($scope.apiPath).then(complete).catch(failed);

        function complete(response, status, headers, config) {
            $scope.users = response.data;
        }

        function failed(e) {
            if (e.data && e.data.description) {
                logger.error('XHR Failed for {0} : {1}', ['read Users', e.data.description]);
            }
        }
    };

    //saving the user data
    $scope.save = function () {

        //checking if there are errors in the form
        $scope.$broadcast('show-errors-check-validity');
        if( $scope.addUserForm.$valid )
        {
            $scope.showError(false);//hide previous error alerts
            if( $scope.newUser.id == 0 )
            {
                $http.post($scope.apiPath, $scope.newUser).then(complete).catch(failed);//adding
            }
            else
            {
                $http.put($scope.apiPath + $scope.newUser.id, $scope.newUser).then(complete).catch(failed);//updating
            }
        }

        function complete(response, status, headers, config) {

            if (response.data.errors === undefined) {
                $scope.successTextAlert = "Saved successfully!";
                $scope.showSuccess(true);
                $scope.init();
            } else {
                $scope.errors = [];
                if(response.data.errors.password !== undefined)
                    $scope.errors = $scope.errors.concat(response.data.errors.password);
                if(response.data.errors.password_confirmation !== undefined)
                    $scope.errors = $scope.errors.concat(response.data.errors.password_confirmation);
                if(response.data.errors.email !== undefined)
                    $scope.errors = $scope.errors.concat(response.data.errors.email);
                
                $scope.showError(true);
            }
        }
        
        function failed(e) {
            if (e.data && e.data.description) {
                logger.error('XHR Failed for {0} : {1}', ['save Users', e.data.description]);
            }
        }
    };

    //deleting an user
    $scope.delete = function ($index) {
        $http.delete($scope.apiPath + $scope.users[$index].id).then(complete).catch(failed);

        function complete(response, status, headers, config) {
            $scope.successTextAlert = "Deleted successfully!";
            $scope.showSuccess(true);
            $scope.init();
        }

        function failed(e) {
            if (e.data && e.data.description) {
                logger.error('XHR Failed for {0} : {1}', ['delete User', e.data.description]);
            }
        }
    };

    //modal to confirm delete
    $scope.confirmDelete = function ($index) {

        var modalOptions = {
            closeButtonText: 'Cancel',
            actionButtonText: 'Delete User',
            headerText: 'Delete the user "' + $scope.users[$index].name + '" ?',
            bodyText: 'Are you sure you want to delete this ?'
        };

        modalService.showModal({}, modalOptions).then(function (result) {
            $scope.delete($index);
        });
    }

    //alerts -----------------------------------------------------------------------------------------------
    $scope.successTextAlert = "Saved successfully!";
    $scope.showSuccessAlert = false;
    $scope.showSuccess = function(value) {
        $scope.showSuccessAlert = value;
        $timeout(function() { $scope.showSuccessAlert = false; }, 3000);//clean the alert afterwhile
    };
    $scope.errors = [];
    $scope.showErrorAlert = false;
    $scope.showError = function(value) {
        $scope.showErrorAlert = value;
    };

    //initializing the controller
    $scope.init();
}