/**
 * Created by fpauer on 5/8/16.
 */

    angular
        .module('app.meals')
        .controller('MealsController', MealsController);

     MealsController.$inject = ['$state', '$http', '$rootScope', '$scope', '$auth'];

    function MealsController($state, $http, $rootScope, $scope, $auth) {


        $scope.action = "New";

        $scope.caloriesConsumed = function(){
            var total = 0;
            for(var i = 0; i < $scope.meals.length; i++){
                total += ($scope.meals[i].calories);
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
            $scope.newMeal = {
                'id': 0,
                'description': '',
                'calories': '',
                'consumed_at': new Date()
            };
        };
        $scope.clean();
        
        $scope.edit = function (index) {
            $scope.action = "Edit";
            $scope.newMeal = $scope.meals[index];
            
        };
        
        $scope.init = function () {
            //$http.get('/api/meals').success(function (data) {
            //    $scope.meals = data;
            //})
        };
        $scope.init();

        $scope.save = function () {
            $http.post('/api/meals', $scope.newMeal).success(function (data) {
                $scope.meals.push(data);
                $scope.newMeal = {};
                $scope.updateChart();
            });
        };

        $scope.update = function (index) {
            $http.put('/api/meals/' + $scope.meals[index].id, $scope.meals[index]).success(function (data) {
                $scope.newMeal = {};
                $scope.updateChart();
            });
        };

        $scope.delete = function (index) {
            $http.delete('/api/meals/' + $scope.meals[index].id).success(function () {
                $scope.meals.splice(index, 1);
                $scope.updateChart();
            });
        };

        $scope.logout = function () {
            console.log('logout');
            $auth.logout().then(function () {
                $rootScope.currentUser = null;
                $state.go('login');
            });
        };



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
        $scope.chartLabels = ["Consumed", "Expected"];

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
        };
        $scope.updateChart();
        //END - Chart Element ----------------------------------------------------------------------------------------------
    };