<?php use Acme\Auth\Auth; ?>
<!DOCTYPE html>
<html lang="en" ng-app="DIPApp">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
    <title>Digital India Payments </title>
    <link rel="stylesheet" href="/sweetalert/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="/material-design-icons/css/material-design-iconic-font.min.css"/>
    <link rel="stylesheet" href="/css/style.css" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <style>
      .cur { cursor: pointer; }
    </style>
  </head>
  <body class="be-splash-screen">
    <!-- Header logo sidebar -->
    <nav class="navbar navbar-default navbar-fixed-top be-top-header" style="height: 100px; background: whitesmoke;">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <br>
            <div class="col-md-2">
              <img src="/images/cinqueterre.png" height="50px;" style="margin-left: -40px;">
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-2">
              <img src="/images/rbl.png" height="50px;">
            </div>
          </div>
        </div>
      </div>
    </nav>
    <!-- End header sidebar -->
    <div ng-controller="LoginCtrl" class="col-md-4 col-md-offset-4 head-weight">
      <div class="be-wrapper be-login">
        <div class="be-content">
          <div class="main-content container-fluid">
            <div class="splash-container" style="margin: 116px auto;">
              <div class="panel panel-default panel-border-color panel-border-color-primary" >
                <div class="panel-heading">
                  <h4>USER LOGIN</h4>
                  <!-- <h4 ng-show="showPasswordResetFrm">USER LOGIN</h4> -->
                </div>
                <div class="panel-body">
                  <form name="loginFrm" ng-show="frmState == 0" novalidate ng-submit="login(user)">
                    <div class="form-group">
                      <input type="text" id="phone_no" ng-model="user.phone_no"  name="phone_no" placeholder="Mobile Number" class="form-control" isnumber required>
                    </div>
                    <div class="form-group">
                      <input id="password" ng-model="user.password" type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group row login-tools">
                      <div class="col-xs-6 login-forgot-password" style="line-height: 0px; float:right;"><a ng-click="forgotPassword()" class="cur">Forgot Password?</a></div>
                    </div>
                    <div class="form-group login-submit">
                      <button ng-disabled="true" type="submit" name="login" class="btn btn-primary btn-xl"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;LOGIN</button>
                    </div>
                  </form>
                  <form name="passwordResetFrm" ng-show="frmState == 1" ng-submit="getOTP(otpObj)">
                    <div class="form-group">
                      <input type="text" ng-model="otpObj.phone_no"  name="phone_no" placeholder="Mobile Number" class="form-control" isnumber required>
                    </div>
                    <div class="form-group login-submit">
                      <button type="submit" class="btn btn-primary btn-xl">Send OTP</button>
                    </div>
                  </form>
                  <form name="newPasswordFrm" ng-show="frmState == 2" ng-submit="passwordReset(passwordResetObj)">
                    <div class="form-group">
                      <input ng-model="passwordResetObj.otp" type="text" name="otp" placeholder="OTP" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <input ng-model="passwordResetObj.password" type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <input ng-model="passwordResetObj.password_confirmation" type="text" name="password_confirmation" placeholder="Password Confirmation" class="form-control" required>
                    </div>
                    <div class="form-group login-submit">
                      <button type="submit" class="btn btn-primary btn-xl">Reset password</button>
                    </div>
                  </form>
                </div>
              </div>
              <!-- Can Add Signup Link -->
            </div>
            <div class="footer" align="center" style="margin-top: -80px;">
              <!--<img src="images/digital.png" height="50px;">-->
              &nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/adhar.png" height="50px;"> <br>
              <h6>Digital India Payments Limited. Copyright 2017.</h6>
            </div>
          </div>
        </div>
      </div>
      <!-- <form ng-submit="login(user)">
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
        </div> -->
    </div>
    <script src="/sweetalert/sweetalert.min.js"></script>
    <script src="/components/jquery/dist/jquery.min.js"></script>
    <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/components/angular/angular.min.js"></script>
    <script src="/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script>
      angular.module('DIPApp', [])
        .controller('LoginCtrl', ['$scope', '$http', function ($scope, $http) {
          window.s = $scope
          $scope.frmState = 0
          $scope.login = login
          $scope.forgotPassword = forgotPassword
          $scope.getOTP = getOTP
          $scope.passwordReset = passwordReset
          $scope.otpVerification = otpVerification


          function login (user) {
            if (! user.phone_no || ! user.password) {
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
          function forgotPassword () {
            $scope.frmState = 1
          }
          function getOTP (obj) {
            if ($scope.passwordResetFrm.$invalid) {
              sweetAlert('Error', 'Please fill the phone number', 'warning')
              return
            }
            $http.post('/api/v1/actions/password-reset-otp', obj)
              .then(function () {
                $scope.frmState = 2
                $scope.passwordResetObj = { phone_no: obj.phone_no }
              }, function (err) {
                if (err.data.code == 1) {
                  sweetAlert('Error', 'Phone number is not registered with us', 'error')
                  return
                }
                sweetAlert('Error', 'Something we\'nt wrong. Try again later.', 'error')
              })
          }
          function passwordReset (obj) {
            if ($scope.newPasswordFrm.$invalid) {
              sweetAlert('Error', 'Please fill all the fields', 'warning')
              return
            }
            if (obj.password != obj.password_confirmation) {
              sweetAlert('Error', 'Password and password confirmation do not match', 'warning')
              return
            }
            $http.post('/api/v1/actions/new-password-otp', obj)
              .then(function () {
                sweetAlert('Success', 'Password changed successfully', 'success')
                window.location.href = '/'
              }, function (err) {
                if (err.data.code && err.data.code == 1) {
                  sweetAlert('Error', 'Invalid OTP or OTP expired.', 'error')
                  return
                }
                sweetAlert('Error', 'Try again later.', 'error')
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
    <!-- <script src="assets/js/main.js" type="text/javascript"></script> -->
    </script>
  </body>
</html>
