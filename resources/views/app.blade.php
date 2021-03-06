<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meals and Calories</title>
    <link rel="stylesheet" href="{{ URL::asset('node_modules/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>
<body ng-app="app.meals">

<nav class="navbar navbar-default navbar-static-top" ng-if="currentUser.name">
    <div class="container">
        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            <ul class="nav navbar-nav navbar-left">
                <li ng-repeat="menu in menus" ng-if="menu.link" ui-sref-active="active"><a ui-sref="@{{menu.link}}"><i class="fa fa-btn fa-sign-out"></i>@{{menu.title}}</a></li>
                <li ui-sref-active="active"><a ui-sref="settings"><i class="fa fa-btn fa-sign-out"></i>Settings</a></li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href >Hello, @{{currentUser.name}}</a></li>
                <li><a href="#" ng-click="logout()"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div ui-view></div>

</body>

<script src="{{ URL::asset('node_modules/angular/angular.min.js') }}"></script>
<script src="{{ URL::asset('node_modules/angular-ui-router/release/angular-ui-router.min.js') }}"></script>
<script src="{{ URL::asset('node_modules/angular-animate/angular-animate.min.js') }}"></script>
<script src="{{ URL::asset('node_modules/satellizer/satellizer.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/d3.min.js') }}" ></script>
<script src="{{ URL::asset('js/plugins/pie-chart.min.js') }}" ></script>
<script src="{{ URL::asset('js/plugins/ui-bootstrap/ui-bootstrap-tpls-1.3.2.js') }}"></script>

<script src="{{ URL::asset('js/all.min.js') }}"></script>
<script src="{{ URL::asset('js/tests/apiTest.js') }}"></script>
</html>

