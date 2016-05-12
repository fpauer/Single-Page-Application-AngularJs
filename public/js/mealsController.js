/**
 * Created by fpauer on 5/8/16.
 */

    angular
        .module('app.meals')
        .controller('MealsController', MealsController);

     MealsController.$inject = ['$state', '$http', '$rootScope', '$scope', '$auth', '$timeout', 'modalService'];

    function MealsController($state, $http, $rootScope, $scope, $auth, $timeout, modalService) {

        $scope.apiPath = '/api/user/'+$rootScope.currentUser.id+'/meals/';
        $scope.hour = new Date().getHours();
        $scope.minute = new Date().getMinutes();
        $scope.hourFrom = 0;
        $scope.minuteFrom = 0;
        $scope.hourTo = 23;
        $scope.minuteTo = 59;
        $scope.action = "New";

        $scope.caloriesConsumed = function(){
            var total = 0;
            for(var i = 0; i < $scope.meals.length; i++){
                total += parseInt($scope.meals[i].calories);
            }
            return total;
        }

        $scope.meals = [
            {
                'id': 1,
                'description': 'Ceaser Salad', 
                'calories': 123, 
                'user_id': 1,
                'consumed_at': new Date('2016-05-10T10:08:50')
            },
            {
                'id': 2,
                'description': 'Pasta',
                'calories': 567,
                'user_id': 1,
                'consumed_at': new Date('2016-05-01T10:08:50')
            },
            {
                'id': 3,
                'description': 'T-bone',
                'calories': 800,
                'user_id': 1,
                'consumed_at': new Date('2016-05-05T18:08:50')
            },
            {
                'id': 4,
                'description': 'Ceaser Salad',
                'calories': 123,
                'user_id': 1,
                'consumed_at': new Date('2016-05-06T10:08:50')
            },
        ];
        
        $scope.clean = function (index) {
            $scope.action = "New";
            $scope.hour = new Date().getHours();
            $scope.minute = new Date().getMinutes();
            $scope.newMeal = {
                'id': 0,
                'description': '',
                'calories': '',
                'consumed_at': new Date()
            };
            $scope.$broadcast('show-errors-reset');

        };
        $scope.clean();
        
        $scope.edit = function (index) {
            $scope.action = "Edit";
            $scope.newMeal = $scope.meals[index];
            if( typeof $scope.newMeal.consumed_at == 'string')
            {
                $scope.newMeal.consumed_at = new Date($scope.newMeal.consumed_at.replace(/\s/, 'T'));
            }
            $scope.hour = $scope.newMeal.consumed_at.getHours();
            $scope.minute = $scope.newMeal.consumed_at.getMinutes();
        };
        
        $scope.init = function () {
            $http.get($scope.apiPath).then(complete).catch(failed);

            function complete(response, status, headers, config) {
                $scope.meals = response.data;
                $scope.clean();
                $scope.updateChart();
            }

            function failed(e) {
                var newMessage = 'XHR Failed for getCustomer'
                if (e.data && e.data.description) {
                    newMessage = newMessage + '\n' + e.data.description;
                }
                e.data.description = newMessage;
                logger.error(newMessage);
                return $q.reject(e);
            }
        };

        $scope.save = function () {

            $scope.$broadcast('show-errors-check-validity');
            if( $scope.addMealForm.$valid )
            {
                $scope.newMeal.consumed_at.setUTCHours( parseInt($scope.hour) );
                $scope.newMeal.consumed_at.setMinutes( parseInt($scope.minute) );

                if( $scope.newMeal.id == 0 )
                {
                    $http.post($scope.apiPath, $scope.newMeal).then(complete);
                }
                else
                {
                    $http.put($scope.apiPath + $scope.newMeal.id, $scope.newMeal).then(complete);
                }
            }

            function complete(response, status, headers, config) {
                $scope.successTextAlert = "Saved successfully!";
                $scope.showSuccess(true);
                $scope.init();
            }
        };

        $scope.delete = function ($index) {
            $http.delete($scope.apiPath + $scope.meals[$index].id).success(function () {
                $scope.successTextAlert = "Deleted successfully!";
                $scope.showSuccess(true);
                $scope.init();
            });
        };

        $scope.logout = function () {
            $auth.logout().then(function () {
                $rootScope.currentUser = null;
                $state.go('login');
            });
        };

        $scope.confirmDelete = function ($index) {

            var modalOptions = {
                closeButtonText: 'Cancel',
                actionButtonText: 'Delete Meal',
                headerText: 'Delete the meal "' + $scope.meals[$index].description + '" ?',
                bodyText: 'Are you sure you want to delete this ?'
            };

            modalService.showModal({}, modalOptions).then(function (result) {
                $scope.delete($index);
            });
        }



        // DatePicker Elements --------------------------------------------------------------------------------
        $scope.format = 'dd-MM-yyyy';
        $scope.formatFull = 'dd-MM-yyyy HH:mm';
        $scope.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(),
            startingDay: 1
        };

        $scope.today = function() {
            $scope.dateFrom = new Date();
            $scope.dateTo = new Date();
        };
        $scope.today();

        $scope.open1 = function() {
            $scope.popup1.opened = true;
        };

        $scope.open2 = function() {
            $scope.popup2.opened = true;
        };

        $scope.openEdit = function() {
            $scope.popupEdit.opened = true;
        };

        $scope.popup1 = {
            opened: false
        };

        $scope.popup2 = {
            opened: false
        };

        $scope.popupEdit = {
            opened: false
        };


        // END - DatePicker Controler --------------------------------------------------------------------------------

        //Chart Element ----------------------------------------------------------------------------------------------
        $scope.initChart = function()
        {
            $scope.chartData = {
                globals: {
                    shadow: false,
                    fontFamily: "Verdana",
                    fontWeight: "100"
                },
                type: "pie",
                backgroundColor: "#f5f5f5",

                tooltip: {
                    text: "%v %t"
                },
                plot: {
                    refAngle: "-90",
                    borderWidth: "0px",
                    valueBox: {
                        placement: "in",
                        text: "%npv %",
                        fontSize: "15px",
                        textAlpha: 1,
                    }
                },
                series: [{
                    text: "Consumed",
                    values: [0],
                    backgroundColor: "#FA6E6E #FA9494",
                }, {
                    text: "Available",
                    values: [ 0 ],
                    backgroundColor: "#D2D6DE",
                }]
            };

        };
        $scope.initChart();

        $scope.updateChart = function()
        {
            var caloriesConsumed = $scope.caloriesConsumed();
            $scope.chartData.series[0].values = [caloriesConsumed];

            var remained = parseInt($rootScope.numberOfCalories)-caloriesConsumed;
            if( remained<0 )
            {
                $scope.chartData.series[0].backgroundColor = "#FA6E6E #FA9494";
                $scope.chartData.series[1].values = [0];
            }
            else
            {
                $scope.chartData.series[0].backgroundColor = "#28C2D1";
                $scope.chartData.series[1].values = [remained];
            }
            //console.log($scope.chartData.series);
        };
        $scope.updateChart();
        //END - Chart Element ----------------------------------------------------------------------------------------------

        //alert methos (maybe a directive) ---------------------------------------------------------------------------------
        $scope.successTextAlert = "Saved successfully!";
        $scope.showSuccessAlert = false;
        $scope.showSuccess = function(value) {
            $scope.showSuccessAlert = value;
            $timeout(function() { $scope.showSuccessAlert = false; }, 3000);
        };
        $scope.showErrorAlert = false;
        $scope.showError = function(value) {
            $scope.showErrorAlert = value;
        };

        $scope.init();
    };