<!DOCTYPE html>
<html dir="ltr" lang="en-US" ng-app="App">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="/landing-ext/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/et-line.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/style.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/anil.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/dark.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/font-icons.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/animate.css" type="text/css">
    <link rel="stylesheet" href="/landing-ext/css/responsive.css" type="text/css">
    <link rel="stylesheet" href="/components/toastr/toastr.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="/landing-ext/font-awesome/css/font-awesome.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <title>Gold blockchain</title>
    <div class="fit-vids-style">
      ­
      <style>
        .fluid-width-video-wrapper { width: 100%; position: relative; padding: 0; }
        .fluid-width-video-wrapper iframe, .fluid-width-video-wrapper object, .fluid-width-video-wrapper embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%;}
      </style>
    </div>
    <div class="fit-vids-style">
      ­
      <style>
        .fluid-width-video-wrapper { width: 100%; position: relative; padding: 0; }
        .fluid-width-video-wrapper iframe, .fluid-width-video-wrapper object, .fluid-width-video-wrapper embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
      </style>
    </div>
    <!-- <script type="text/javascript" async src="/landing-ext/js/baao0168"></script><script async src="/landing-ext/js/analytics.js"></script> -->
  </head>
  <body class="no-transition device-lg" ng-controller="LandingCtrl">
    <div class="clearfix">
      <section id="page-title" class="hidden page-title-parallax" style="background-position: 50% -45px;" data-stellar-background-ratio="0.3"> </section>
     
      <section id="content">
        <div class="content-wrap">
          <div class="section nomargin header-stick bg-white">
            <div class="container clearfix">
              <div class="heading-block center topmargin-lg">
                <h2>Reset password</h2>
                <form ng-submit="resetPassword(resetPwdObj)" name="resetPasswordFrm" class="col-md-4 col-md-offset-4" >
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" ng-minlength="5" name="password" ng-model="resetPwdObj.password" required class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password" ng-model="resetPwdObj.password_conf" required class="form-control">
                  </div>
                  <button class="btn btn-primary">Reset password</button>
                </form>
              </div>
              <div class="clear bottommargin-sm"></div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
      </section>
      <div class="footer">
        <div class="container">
          <div class="col-md-12">
            <div class="col-md-2">
              <ul>
                <h3>DOCUMENTATION</h3>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">KYC & Anti Fraud</a></li>
              </ul>
            </div>
            <div class="col-md-2">
              <ul>
                <h3>HOW IT WORKS</h3>
                <li><a href="#" target="_blank">How to use</a></li>
                <li><a href="#">for Beginners</a></li>
                <li><a href="#" target="_blank">Practice Trading</a></li>
              </ul>
            </div>
            <div class="col-md-2">
              <ul>
                <h3>TOOLS</h3>
                <li><a href="#">Trade API</a></li>
                <li><a href="#" target="_blank">Block Explorer</a></li>
              </ul>
            </div>
            <div class="col-md-2">
              <ul>
                <h3>GOLDCOIN</h3>
                <li><a href="#" target="_blank">Our Home Page</a></li>
                <li><a href="#" target="_blank">Blog</a></li>
                <li><a href="#">Contact & Support</a></li>
                <li><a href="#">About Us</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <a class="twitter-timeline" href="https://twitter.com/GoldChain"  data-chrome="nofooter transparent noborders" data-widget-id="694333031482540032" width="300" height="140">Tweets by @GoldChain</a>
              <script>
                !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
              </script>
            </div>
          </div>
          <div class="col-md-4">
            <p> © 2017-2018</p>
          </div>
          <div class="col-md-3 mrlft col-md-offset-4"> <a href="#" target="_blank"> <i class="fa fa-facebook noleft"></i></a> <a href="#" target="_blank"> <i class="fa fa-google-plus"></i></a> <a href="#" target="_blank"><i class="fa fa-twitter"></i></a> </div>
        </div>
      </div>
    </div>
    <script src="/components/jquery/dist/jquery.min.js"></script>
    <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/components/angular/angular.min.js"></script>
    <script src="/components/toastr/toastr.min.js"></script>
    <script>
      var app = angular.module('App', [])
      app.controller('LandingCtrl', ['$scope', '$http', function ($scope, $http) {
        window.s = $scope
        $scope.resetPassword = resetPassword

        function resetPassword (obj) {
          if ($scope.resetPasswordFrm.$invalid) {
            toastr.error('Fill all the details.')
            return
          }
          if (obj.password !== obj.password_conf) {
            toastr.error('Passwords do not match')
            return
          }
          $http.post(window.location.href, obj)
            .then(function (data) {
              toastr.success('Your password has been reset. Please login to continue.')
              setTimeout(function () {
                window.location.href = '/landing'
              }, 2000)
              $('#forgotPasswordModal').modal('hide')
            }, function (err) {
              if (err.status === 400) {
                toastr.error('Reload the page and try again.')
                return
              }
              toastr.error('Something went wrong.')
            })
        }

        function fail (err) {
          toastr.error('Something wrong occured')
        }
      }])
    </script>
  </body>
</html>