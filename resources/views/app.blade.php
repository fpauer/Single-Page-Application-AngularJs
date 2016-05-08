<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>My meals application</title>
    <link rel="stylesheet" href="{{ URL::asset('node_modules/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>
<body ng-app="app.meals">

<nav class="navbar navbar-default navbar-static-top" ng-if="currentUser.name">
    <div class="container">
        <div class="navbar-header">

            <!-- Branding Image -->
            <a class="navbar-brand" href="#">
                Meals of @{{currentUser.name}}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#" ng-click="logout()"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div ui-view></div>

</body>

<script src="{{ URL::asset('node_modules/angular/angular.js') }}"></script>
<script src="{{ URL::asset('node_modules/angular-ui-router/release/angular-ui-router.js') }}"></script>
<script src="{{ URL::asset('node_modules/satellizer/satellizer.js') }}"></script>
<script src="{{ URL::asset('js/showErrors/showErrors.js') }}"></script>

<script src="{{ URL::asset('js/app.js') }}"></script>
<script src="{{ URL::asset('js/authController.js') }}"></script>
<script src="{{ URL::asset('js/mealsController.js') }}"></script><!DOCTYPE html>

