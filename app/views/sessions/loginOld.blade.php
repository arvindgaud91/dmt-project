<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="LoginCtrl" class="col-md-4 col-md-offset-4 head-weight">
    <form ng-submit="login(user)">
      <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" ng-model="user.email" required class="form-control">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" ng-model="user.password" required class="form-control">
      </div>
      <button class="btn btn-primary" type="submit">Login</button>
    </form>

    <div id="OTPModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Verify OTP</h4>
          </div>
          <div class="modal-body row">
            <div class="col-md-12">
              <form ng-submit="otpVerification(otpObj)" name="OTPFrm">
                <div class="form-group">
                  <label>OTP</label>
                  <input required ng-model="otpObj.otp" type="text" class="form-control">
                </div>
                <input class="btn btn-primary" type="submit" value="Verify mobile number">
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('LoginCtrl', ['$scope', '$http', function ($scope, $http) {
      $scope.login = login
      $scope.otpVerification = otpVerification

      function login (user) {
        if (! user.email || ! user.password) {
          sweetAlert('Incomplete', 'Please fill all details.', 'error')
          return
        }
        $http.post('/login', user)
          .then(function (data) {
            sweetAlert('Success', 'Successfully logged in.', 'success')
            setTimeout(function () {
              window.location.href = '/'
            }, 1500)
          }, function (err) {
            if (err.data.code && err.data.code == 2) {
              sweetAlert('Email verification pending', 'Please verify you\'re email to log in.', 'error')
              return
            }
            if (err.data.code && err.data.code == 3) {
              sweetAlert('Phone number verification pending', 'Please verify you\'re mobile number to log in.', 'error')
              $scope.otpObj = {email: err.data.email}
              showOTPModal(err.data)
              return
            }
            sweetAlert('Invalid credentials', 'Email & password do not match.', 'error')
          })
      }
      function otpVerification (obj) {
        if ($scope.OTPFrm.$invalid) {
          toastr.error('Fill all the details.')
          return
        }
        $http.post('/verification/phone/'+obj.otp, obj)
          .then(function (data) {
            sweetAlert('Success', 'Mobile no. is verified. Please login to continue.', 'success')
            $('#OTPModal').modal('hide')
          }, function (err) {
            if (err.status == 400) {
              sweetAlert('Error', 'Please send email', 'error')
              return
            }
            sweetAlert('Error', 'OTP entered is invalid', 'error')
          })
      }

      function showOTPModal (obj) {
        $('#OTPModal').modal('show')
      }

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
