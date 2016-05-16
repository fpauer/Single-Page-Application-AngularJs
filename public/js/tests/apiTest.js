/**
 * Created by Fernando on 5/15/16.
 */

/* Tests */

describe('Meals app tests', function () {

    var $httpBackend;
    var $scope;

    beforeEach(module('app.meals'));

    beforeEach(inject(function(_httpBackend_, $rootScope) {
        $scope = $rootScope.$new();
        $httpBackend = _httpBackend_;
    }));



    describe('http test (when vs expect)', function () {

        it('Testing user login', function() {

            var $scope = {};
            var $token = {};

            /* Code Under Test */
            var loginuser = {'email': 'admin@admin.com', 'password': '@dmin1'};
            $httpBackend.post('/api/auth/authenticate', loginuser)
                .success(function(data, status, headers, config) {
                    $scope.valid = true;
                    $scope.response = data;
                    $token = data.token;
                }).error(function(data, status, headers, config) {
                $scope.valid = false;
            });
            /* End */


            //$httpBackend
            //    .expect('GET', 'http://localhost/foo')
            //    .respond(200, { foo: 'bar' });

            //expect($httpBackend.flush).not.toThrow();

        });
    });

});
