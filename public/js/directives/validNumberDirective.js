/**
 * Created by Fernando on 5/11/16.
 * 
 * Directive to guarantee that input has only numbers
 * 
 */
angular
    .module('app.meals')
    .directive('validNumber', function() {
    return {
        require: '?ngModel', //it will wokr directly in the ngModel value
        link: function(scope, element, attrs, ngModelCtrl) {
            if(!ngModelCtrl) {
                return;
            }

            //using parsers to pus hthe val
            ngModelCtrl.$parsers.push(function(val) {
                if (angular.isUndefined(val)) {
                    var val = '';
                }

                //cleaning any characters different from number
                var clean = val.replace(/[^0-9]/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });

            element.bind('keypress', function(event) {
                if(event.keyCode === 32) {
                    event.preventDefault();
                }
            });
        }
    };
});