<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="RegisterCtrl" class="col-md-4 col-md-offset-4 head-weight">
    <form ng-submit="register(user)">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" ng-model="user.name" required class="form-control">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" ng-model="user.email" required class="form-control">
      </div>
      <div class="form-group">
        <label>Mobile no</label>
        <input type="text" name="mobile_no" ng-model="user.phone_no" required class="form-control">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" ng-model="user.password" required class="form-control">
      </div>
      <div class="form-group">
        <label>Confirm password</label>
        <input type="password" name="password_conf" ng-model="user.password_conf" required class="form-control">
      </div>
      <button class="btn btn-primary" type="submit">Register</button>
    </form>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('RegisterCtrl', ['$scope', '$http', function ($scope, $http) {
      $scope.register = register

      function register (user) {
        if (! user.email || ! user.password) {
          sweetAlert('Incomplete', 'Please fill all details.', 'error')
          return
        }
        $http.post('/register', user)
          .then(function (data) {
            sweetAlert('Success', 'Registration is successfully done. Please login to continue.', 'success')
            setTimeout(function () {
              window.location.href = '/login'
            }, 4000)
          }, fail)
      }

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
