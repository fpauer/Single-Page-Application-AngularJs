<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>My meals application</title>
    <link rel="stylesheet" href="{{ URL::asset('node_modules/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>
<body ng-app="app.meals">

<nav class="navbar navbar-default navbar-static-top" ng-controller="MenuController as menuCtrl" ng-if="currentUser.name">
    <div class="container">
        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            <ul class="nav navbar-nav navbar-left" ng-repeat="menu in menus">
                <li><a ui-sref="@{{menu.link}}" ng-class="{ active:menu.active}"><i class="fa fa-btn fa-sign-out"></i>@{{menu.title}}</a></li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a ui-sref="settings"><i class="fa fa-btn fa-sign-out"></i>Settings</a></li>
                <li><a href="#" ng-click="logout()"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div ui-view></div>

</body>

<script src="{{ URL::asset('node_modules/angular/angular.js') }}"></script>
<script src="{{ URL::asset('node_modules/angular-ui-router/release/angular-ui-router.js') }}"></script>
<script src="{{ URL::asset('node_modules/angular-animate/angular-animate.js') }}"></script>
<script src="{{ URL::asset('node_modules/satellizer/satellizer.js') }}"></script>
<script src="{{ URL::asset('js/plugins/ui-bootstrap/ui-bootstrap-tpls-1.3.2.js') }}"></script>
<script src="{{ URL::asset('js/showErrors/showErrors.js') }}"></script>
<script src="{{ URL::asset('js/plugins/zingchart/zingchart.min.js') }}" ></script>
<script src="{{ URL::asset('js/plugins/zingchart/zingchart-angularjs.js') }}" ></script>

<script src="{{ URL::asset('js/app.js') }}"></script>
<script src="{{ URL::asset('js/authController.js') }}"></script>
<script src="{{ URL::asset('js/mealsController.js') }}"></script>
<script src="{{ URL::asset('js/menuController.js') }}"></script>
<script src="{{ URL::asset('js/usersController.js') }}"></script>
<script src="{{ URL::asset('js/settingsController.js') }}"></script>

</html>

