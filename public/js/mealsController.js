/**
 * Created by Fernando on 5/8/16.
 *
 * Meal Controller used by all user
 * 
 * - Create a new meal
 * - Update a meal
 * - Deleta a meal
 * - Find the meals consumed by date and time
 * - Chart to show what is the available calories for today
 * 
 * @param $http
 * @param $rootScope
 * @param $scope
 * @param $timeout
 * @param $filter
 * @param Logger
 * @param modalService
 * @constructor
 */

    angular
        .module('app.meals')
        .controller('MealsController', MealsController);


    angular
        .module('app.meals')
        .filter("formatter",function(){
            return function(item){
                return ("0" + item).slice(-2);
            }
        });

     MealsController.$inject = ['$http', '$rootScope', '$scope', '$timeout', '$filter', 'Logger', 'modalService'];

    function MealsController($http, $rootScope, $scope, $timeout, $filter, Logger, modalService) {

        var logger = Logger.getInstance('MealsController');
        $scope.apiDataFormat =  'yyyy-MM-dd';
        $scope.apiPath = '/api/user/';
        $scope.meals = [];
        $scope.action = "New";
        $scope.hour = new Date().getHours();
        $scope.minute = new Date().getMinutes();
        $scope.hourFrom = 0;
        $scope.minuteFrom = 0;
        $scope.hourTo = 23;
        $scope.minuteTo = 59;
        $scope.caloriesConsumedToday = 0;
        if( $rootScope.currentUser!= null) $scope.userSelected = $rootScope.currentUser.id;

        //list of users
        $scope.users = [];
        $scope.loadUsers = function () {
            if( $rootScope.currentUser!= null) $http.get($scope.apiPath+$rootScope.currentUser.id+'/users').then(complete).catch(failed);

            function complete(response, status, headers, config) {
                $scope.users = response.data;
            }

            function failed(e) {
                if (e.data && e.data.description) {
                    logger.error('XHR Failed for {0} : {1}', ['read Users', e.data.description]);
                }
            }
        };
        $scope.loadUsers();

        //cleaning the new/edit from
        $scope.clean = function (index) {
            $scope.$broadcast('show-errors-reset');//reseting the directive show-errors
            $scope.showError(false);
            $scope.action = "New";
            $scope.hour = new Date().getHours();
            $scope.minute = new Date().getMinutes();
            $scope.newMeal = {
                'id': 0,
                'description': '',
                'calories': '',
                'consumed_at': new Date()
            };
        };

        //setting data to edit from
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

        //searching the meals for the user
        $scope.search = function(){

            var urlSearch = $scope.apiPath+$scope.userSelected+'/meals';
            urlSearch   += '/'+$filter('date')($scope.dateFrom, $scope.apiDataFormat);
            urlSearch   += '/'+$filter('date')($scope.dateTo, $scope.apiDataFormat);
            urlSearch   += '/'+$filter('formatter')($scope.hourFrom)+':'+$filter('formatter')($scope.minuteFrom);
            urlSearch   += '/'+$filter('formatter')($scope.hourTo)+':'+$filter('formatter')($scope.minuteTo);

            $http.get(urlSearch).then(complete).catch(failed);

            function complete(response, status, headers, config) {
                $scope.meals = response.data;
                for(var i = 0; i < $scope.meals.length; i++){
                    if( typeof $scope.meals[i].consumed_at == 'string')
                    {
                        $scope.meals[i].consumed_at = new Date($scope.meals[i].consumed_at.replace(/\s/, 'T'));
                    }
                }
            }

            function failed(e) {
                if (e.data && e.data.description) {
                    logger.error('XHR Failed for {0} : {1}', ['search', e.data.description]);
                }
            }

        };

        //saving a new or exist meal
        $scope.save = function () {

            $scope.$broadcast('show-errors-check-validity');
            if( $scope.addMealForm.$valid )
            {
                var newDate = $scope.newMeal.consumed_at;
                //fixing problems with timezone
                var year = newDate.getUTCFullYear();
                var month = newDate.getUTCMonth();
                var day = newDate.getUTCDate();
                if( newDate.getDate()!=day) day = newDate.getDate();
                newDate.setUTCHours( parseInt($scope.hour) );
                newDate.setMinutes( parseInt($scope.minute) );
                newDate.setUTCFullYear( year );
                newDate.setUTCMonth( month );
                newDate.setUTCDate( day );
                $scope.newMeal.consumed_at = newDate;

                if( $scope.newMeal.id == 0 )
                {
                    $scope.userSelected = $rootScope.currentUser.id;
                    $http.post($scope.apiPath+$scope.userSelected+'/meals', $scope.newMeal).then(complete).catch(failed);//create
                }
                else
                {
                    $http.put($scope.apiPath+$scope.newMeal.user_id+'/meals/' + $scope.newMeal.id, $scope.newMeal).then(complete).catch(failed);//update
                }
            }

            function complete(response, status, headers, config) {
                $scope.successTextAlert = "Saved successfully!";
                $scope.showSuccess(true);
                $scope.init();
            }
            function failed(e) {
                if (e.data && e.data.description) {
                    logger.error('XHR Failed for {0} : {1}', ['save meal', e.data.description]);
                }
            }
        };

        //deleting the meal
        $scope.delete = function ($index) {
            $http.delete($scope.apiPath+$scope.userSelected+'/meals/' + $scope.meals[$index].id).then(complete).catch(failed);
            function complete(response, status, headers, config) {
                $scope.successTextAlert = "Deleted successfully!";
                $scope.showSuccess(true);
                $scope.init();
            }
            function failed(e) {
                if (e.data && e.data.description) {
                    logger.error('XHR Failed for {0} : {1}', ['delete meal', e.data.description]);
                }
            }
        };

        //confirming if really want to delete the meal
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
        $scope.format = 'MM-dd-yyyy';
        $scope.formatFull = 'MM-dd-yyyy HH:mm';
        $scope.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(),
            startingDay: 1
        };

        //options for dateFrom picker date
        $scope.dateFromOptions = {
            formatYear: 'yy',
            maxDate: new Date(),
            startingDay: 1
        };

        //options for dateTo picker date
        $scope.dateToOptions = {
            formatYear: 'yy',
            minDate: $scope.dateFrom,
            maxDate: new Date(),
            startingDay: 1
        };

        //setting dates to today
        $scope.today = function() {
            $scope.dateFrom = new Date();
            $scope.dateTo = new Date();
        };
        $scope.today();

        //open dateFrom popup
        $scope.openDateFrom = function() {
            $scope.popupDateFrom.opened = true;
        };

        //open dateTo popup
        $scope.openDateTo = function() {
            $scope.popupDateTo.opened = true;
        };

        //open datepicker from form
        $scope.openEdit = function() {
            $scope.popupEdit.opened = true;
        };

        $scope.popupDateFrom = {
            opened: false
        };

        $scope.popupDateTo = {
            opened: false
        };

        $scope.popupEdit = {
            opened: false
        };
        // END - DatePicker Controler --------------------------------------------------------------------------------

        //Chart Element ----------------------------------------------------------------------------------------------
        $scope.initChart = function()
        {
            $scope.chartOptions = {thickness: 10};
            $scope.chartData = [
                {label: "Consumed", value: 0, color: "#FA6E6E"},
                {label: "Available", value: 0, color: "#93b874"}
            ];
        };

        $scope.updateChart = function()
        {
            //getting from REST API the calories consumed for today
            var urlToday = $scope.apiPath+$scope.currentUser.id+'/meals';
            urlToday   += '/'+$filter('date')(new Date(),$scope.apiDataFormat);
            urlToday   += '/'+$filter('date')(new Date(), $scope.apiDataFormat)+'/00:00/23:59';

            $http.get(urlToday).then(complete).catch(failed);

            function complete(response, status, headers, config) {

                $scope.caloriesConsumedToday = 0;
                var todayMeals = response.data;
                for(var i = 0; i < todayMeals.length; i++){
                    $scope.caloriesConsumedToday += parseInt(todayMeals[i].calories);
                }

                $scope.chartData[0].label = $scope.caloriesConsumedToday + ' Consumed';//updating today calories consumed
                $scope.chartData[0].value = $scope.caloriesConsumedToday;//updating today calories consumed
                var remained = parseInt($rootScope.numberOfCalories)-$scope.caloriesConsumedToday;//calculating today available calories

                //Changing the color based on the calories available for today
                if( remained<0 )
                {
                    $scope.chartData = [
                        {label: $scope.caloriesConsumedToday + " Consumed", value: parseInt($scope.caloriesConsumedToday), color: "#FA6E6E"},
                        {label: "Enough for Today!", value: 0, color: "#93b874"}
                    ];
                }
                else
                {
                    $scope.chartData = [
                        {label: $scope.caloriesConsumedToday + " Consumed", value: parseInt($scope.caloriesConsumedToday), color: "#FA6E6E"},
                        {label: remained + " Available", value: remained, color: "#93b874"}
                    ];
                }
            }

            function failed(e) {
                if (e.data && e.data.description) {
                    logger.error('XHR Failed for {0} : {1}', ['getting calories consumed', e.data.description]);
                }
            }
        };
        //END - Chart Element ----------------------------------------------------------------------------------------------

        //alerts   ---------------------------------------------------------------------------------------------------------
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
        // END - alerts   --------------------------------------------------------------------------------------------------

        //creating a watch to refresh the query
        $scope.$watchGroup(['userSelected', 'dateFrom', 'dateTo', 'hourFrom', 'minuteFrom', 'hourTo', 'minuteTo'], watchSearch);

        function watchSearch(newVal, oldVal)
        {
            $scope.dateFromOptions.maxDate = $scope.dateTo;//avoiding DateFrom bigger than DateTo
            $scope.dateToOptions.minDate = $scope.dateFrom;//avoiding DateTo smaller than DateFrom

            $scope.search();//if any fields changes, search again
        }

        //checking permissions
        $scope.canEdit = function()
        {
            if($scope.currentUser!=null && $scope.userSelected==$scope.currentUser.id) return true;
            else return ($scope.currentUser!=null && $scope.userSelected!=$scope.currentUser.id && $scope.currentUser.role>2);
        }
        
        //initialiazing the controller
        $scope.init = function () {
            if( $rootScope.currentUser!= null)
            {
                $scope.search();
                $scope.clean();
                $scope.initChart();
                $scope.updateChart();
                $scope.updateChart();
            }
        };
        $scope.init();
    };