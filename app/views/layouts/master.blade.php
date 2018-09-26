<?php use Acme\Auth\Auth;
	$user = Auth::user();

    $aeps = "#";
    $dmt = "#";
    $cpt = "#";
    $irctc="#";
    $indonepal="#";
    $playwin="#";

    $aeps = getenv('AUTH_URL');
    $dmt = getenv('DMT_URL');
    $cpt = getenv('CP_URL');
    $irctc = getenv('IRCTC_URL');
    $indonepal = getenv('INDONEPAL_URL');
    $playwin = getenv('PLAYWIN_URL');
    $domain_data = preg_replace('#^https?://#', '', Request::root());
?>
<!DOCTYPE html>
<html ng-app="DIPApp">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@if($domain_data == 'dmt.mysuravi.com:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>SURAVI</title>
    @elseif($domain_data == 'dmt.lrprch.in:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>LRP MULTI RECHARGE</title>
    @elseif($domain_data == 'dmt.manjilgroup.com:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>MANJIL GROUP</title>
    @elseif($domain_data == 'dmt.indiapaysolution.com:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>INDIA PAY SOLUTION</title>
    @elseif($domain_data == 'dmt.samriddhifoundation.net.in:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>SAMRIDDHI FOUNDATION</title>
    @elseif($domain_data == 'dmtservices.primagemarketing.com:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>PRIMAGE VINCOME PVT. LTD</title>
    @elseif($domain_data == 'dmt.amenitiesservices.in:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>AMENITIES SERVICES</title>
    @elseif($domain_data == 'dmt.ekiosk.in:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>eKiosk</title>
    @elseif($domain_data == 'dmt.aonehub.com:8021')
    <link rel="shortcut icon" href="/images/aonehublogo.jpg">
    <title>AONEHUB</title>
    @elseif($domain_data == 'dmt.zippays.in:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>ZIPPAY</title>
    @elseif($domain_data == 'dmtpearltek.digitalindiapayments.com:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>PEARL TEK</title>
    @elseif($domain_data == 'dmt.akpayments.in:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>A.K ENTERPRISES</title>
    @elseif($domain_data == 'dmt.oneindiapayments.com:8021')
    <link rel="shortcut icon" href="/images/_blank.png">
    <title>ONE INDIA PAYMENT</title>
    @else
    <link rel="shortcut icon" href="favicon.ico">
    <title>Digital India Payments</title>
    @endif
		<link rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="/sweetalert/sweetalert.css">
		<link rel="stylesheet" href="/components/toastr/toastr.min.css">
		<link rel="stylesheet" type="text/css" href="/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
		<link rel="stylesheet" type="text/css" href="/material-design-icons/css/material-design-iconic-font.min.css"/>
		<!-- <link rel="stylesheet" type="text/css" href="assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css"/> -->
		<!-- <link rel="stylesheet" type="text/css" href="assets/lib/jqvmap/jqvmap.min.css"/> -->
		<!-- <link rel="stylesheet" type="text/css" href="assets/lib/datetimepicker/css/bootstrap-datetimepicker.min.css"/> -->
		<!--<link rel="stylesheet" href="/css/style.css" type="text/css"/>-->
        <link rel="stylesheet" href="/css/style-new.css" type="text/css"/>
		<link rel="stylesheet" href="/isteven-angular-multiselect/isteven-multi-select.css">
		<link rel="stylesheet" href="/css/esscale.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
		<link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.css">
        
        <!-- Vendor styles -->
        <link rel="stylesheet" href="/vendor/fontawesome/css/font-awesome.css" />
        <link rel="stylesheet" href="/vendor/metisMenu/dist/metisMenu.css" />
        <link rel="stylesheet" href="/vendor/animate.css/animate.css" />
        <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
        <link rel="stylesheet" href="/fonts/pe-icon-7-stroke/css/helper.css" />
        <link rel="stylesheet" href="/styles/style.css">
        <link rel="stylesheet" href="/vendor/c3/c3.min.css" />
        <link rel="stylesheet" href="/vendor/ladda/dist/ladda-themeless.min.css" />
        
		<!--<style media="screen">
			.head-weight {
				margin-top: 30px;
			}
		</style>-->
        <script src = "http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <SCRIPT type="text/javascript">
    window.history.forward();
    function noBack() { window.history.forward(); }
</SCRIPT>
<script>
window.location.hash="dmt";
window.location.hash="Again-No-back-button";//again because google chrome don't insert first hash into history
window.onhashchange=function(){window.location.hash="dmt";}
</script> 
<script>
      $(document).ready(function() {
         function disablePrev() { window.history.forward() }
         window.onload = disablePrev();
         window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
      });
   </script>

		<style>
			[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
				display: none !important;
			}
			.error-p {
				color: red;
			}
            #logo { padding: 4px 0 0; }
		</style>
	</head>
	<body ng-controller="MainCtrl" ng-cloak class="ng-cloak" onload="noBack();" 
    onpageshow="if (event.persisted) noBack();" onunload="">
		<div id="header">
            <div class="color-line">
            </div>
            <div id="logo" class="light-version">
                @if($domain_data == 'dmt.mysuravi.com:8021')
                <span>
                   <img src="/images/SuraviLogo.png" alt="SURAVI Logo" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmt.lrprch.in:8021')
                <span>
                   <img src="/images/lrprch-logo.png" alt="LRP MULTI RECHARGE LOGO" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmt.manjilgroup.com:8021')
                <span>
                   <img src="/images/manjillogo.png" alt="MANJIL LOGO" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmt.indiapaysolution.com:8021')
                <span>
                   <img src="/images/indiapaysolutionlogo.png" alt="INDIA PAY SOLUTION LOGO" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmt.samriddhifoundation.net.in:8021')
                <span>
                   <img src="/images/samriddhi-logo.png" alt="SAMRIDDHI LOGO" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmtservices.primagemarketing.com:8021')
                <span>
                   <img src="/images/primagelogo.jpg" alt="PRIMAGE VINCOME LOGO" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmt.amenitiesservices.in:8021')
                <span>
                   <img src="/images/amenities_logo.png" alt="AMENITIES SERVICES LOGO" style="height: 50px;"/>
                </span>
               @elseif($domain_data == 'dmt.ekiosk.in:8021')
                <span>
                   <img src="/images/ekiosk_logo.png" alt="eKiosk LOGO" style="height: 50px;"/>
                </span>
                @elseif($domain_data == 'dmt.aonehub.com:8021')
                <span>
                   <img src="/images/aonehublogo.jpg" alt="AONEHUB LOGO" style="height: 50px;"/>
                </span>
                @elseif($domain_data == 'dmt.zippays.in:8021')
                <span>
                   <img src="/images/zippay.png" alt="ZIPPAY LOGO" style="height: 50px;"/>
                </span>
                @elseif($domain_data == 'dmtpearltek.digitalindiapayments.com:8021')
                <span>
                   <img src="/images/pearl-tek-logo.png" alt="PEARL TEK LOGO" style="height: 50px;"/>
                </span>
                @elseif($domain_data == 'dmt.akpayments.in:8021')
                <span>
                   <img src="/images/aklogo.png" alt="AK ENTERPRISES LOGO" style="height: 50px;"/>
                </span>
                @elseif($domain_data == 'dmt.oneindiapayments.com:8021')
                <span>
                   <img src="/images/_blank.png" alt="ONE INDIA PAYMENT LOGO" style="height: 50px;"/>
                </span>
               @else
                <span>
                   <img src="/images/cinqueterre.png" alt="DIPL Logo" />
                </span>
               @endif
            </div>
            <nav role="navigation">
                <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
                @if(\Cookie::get('user_type') == 1)
                <form  method="post" class="navbar-form-custom" style="width: 460px;" action="/api/v1/actions/search/remitter">
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone_no" maxlength="10"  pattern="[0-9]{10}"  pattern="\d*" title="10 Digit mobile number." placeholder="Search Remitter" style="display:  inline-block; width: 180px; float:  left;"  maxlength="10" required>

                        
                        <input style="display:  inline-block; float: left; margin-top: 10px;" type="submit" class="btn btn-success btn-sm" value="Search">
                       
                    </div>
                </form>
                @endif  
                <div class="mobile-menu">
                    <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="collapse mobile-navbar" id="mobile-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a class="" href="login.html">Login</a>
                            </li>
                            <li>
                                <a href="/logout">Logout</a>
                            </li>
                            <li>
                                <a class="" href="profile.html">Profile</a>
                            </li>
                        </ul>
                    </div>
                </div>



                <div class="navbar-right">
                    <ul class="nav navbar-nav no-borders">
                       
                     <!--    <li class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="pe-7s-keypad"></i>
                            </a>
                            <div class="dropdown-menu hdropdown bigmenu animated flipInX">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <a href="http://payaeps.digitalindiapayments.com:8021/services">
                                                <i class="fa fa-id-card-o text-warning" style="font-weight: normal;font-size: 40px;line-height: 46px;"></i>
                                                <h5>SERVICE</h5>
                                            </a>
                                        </td>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </li> -->
                        <li class="dropdown">
                            <a href="/logout">
                                <i class="pe-7s-power c-red" style="color: red;"></i>
                            </a>
                        </li>
                    </ul>
                </div> 
            </nav>
        </div>
        <aside id="menu">
            @include('partials.main-nav')
        </aside>
        <div id="wrapper">
            <div class="content">
                @section('content')
                @show
            </div>
            <!-- Footer-->
            <footer class="footer">
                © Digital India Payments Ltd 2017. All Rights Reserved.
            </footer>
        </div>
		<!-- Modal -->
		<div class="overlay" ng-show="sessionExpired"></div>
		<div class="popup" ng-show="sessionExpired">
			<h4>Session Timeout</h4>
			<br>
			<input type="button" class="btn btn-primary btn-block" ng-click="resetSession()" value="Restart Session" >
			<h6>OR</h6>
			<a href="/logout" class="btn btn-primary btn-block">Logout</a>
		</div>
      <!--Logout Modal-->

    <div class="overlay" ng-show="logoutUser"></div>
    <div class="popup" ng-show="logoutUser">
      <h5>User is currently logged in from another device</h5>
      <br>
      <!-- <input type="button" class="btn btn-primary btn-block" ng-click="resetSession()" value="Restart Session" >
      <h6>OR</h6> -->
      <a href="/logout" class="btn btn-primary btn-block">Logout</a>
    </div>

		<script src="/sweetalert/sweetalert.min.js"></script>
		<script src="/components/jquery/dist/jquery.min.js"></script>
		<script src="/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
		<script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="/components/toastr/toastr.min.js"></script>
		<script src="/js/main.js"></script>
		<script src="/components/angular/angular.min.js"></script>
		<script src="/jquery-flot/jquery.flot.js" type="text/javascript"></script>
		<script src="/jquery-flot/jquery.flot.pie.js" type="text/javascript"></script>
		<script src="/jquery-flot/jquery.flot.resize.js" type="text/javascript"></script>
		<script src="/jquery-flot/plugins/jquery.flot.orderBars.js" type="text/javascript"></script>
		<script src="/jquery-flot/plugins/curvedLines.js" type="text/javascript"></script>
		<script src="/jquery.sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
		<script src="/countup/countUp.min.js" type="text/javascript"></script>
		<script src="/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
		<script src="/jqvmap/jquery.vmap.min.js" type="text/javascript"></script>
		<script src="/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
		<script src="/jquery.maskedinput/jquery.maskedinput.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="/isteven-angular-multiselect/isteven-multi-select.js"></script>
		<script src="/js/ng-file-upload-shim.js"></script>	

	   <script src="/js/ng-file-upload-shim.min.js"></script>	
	   <script src="/js/ng-file-upload.min.js"></script>
        
        <!-- Vendor scripts -->
        <script src="/vendor/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="/vendor/jquery.flot.spline/index.js"></script>
        <script src="/vendor/metisMenu/dist/metisMenu.min.js"></script>
        <script src="/vendor/iCheck/icheck.min.js"></script>
        <script src="/vendor/peity/jquery.peity.min.js"></script>
        <script src="/vendor/sparkline/index.js"></script>
        <!-- App scripts -->
        <script src="/scripts/homer.js"></script>
        <script src="/scripts/charts.js"></script>
        <script src="/vendor/d3/d3.min.js"></script>
        <script src="/vendor/c3/c3.min.js"></script>

	 <script type="text/javascript" src="/js/hub.js"></script>
     <script type="text/javascript" src="/js/client.js"></script>
       <script type="text/javascript" src="/js/es6-promise.auto.min.js"></script>

<script type = "text/javascript">
    window.onload = function () {
        document.onkeydown = function (e) {
            return (e.which || e.keyCode) != 116;
        };
    }
</script>
<script type="text/javascript">
$(document).ready(function () {
    
    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});
</script>
		<script type="text/javascript">
              var IDLE_TIMEOUT = 60*60; //seconds
              var _idleSecondsCounter = 0;
              document.onclick = function() {
                  _idleSecondsCounter = 0;
              };
              document.onmousemove = function() {
                  _idleSecondsCounter = 0;
              };
              document.onkeypress = function() {
                  _idleSecondsCounter = 0;
              };
              window.setInterval(CheckIdleTime, 1000);

              function CheckIdleTime() {
                  _idleSecondsCounter++;
                  var oPanel = document.getElementById("SecondsUntilExpire");
                  if (oPanel)
                      oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
                  if (_idleSecondsCounter >= IDLE_TIMEOUT) {
                     
                      document.location.href = "/logout";
                  }
              }
			$(document).ready(function(){
				//initialize the javascript
				App.init();
				// App.dashboard();
			 // App.masks();

			});
		</script>
		<script type="text/javascript">
			$(".sidebar-elements li").click(function() {
				if ($(".sidebar-elements li").removeClass("active")) {
						$(this).removeClass("active");
				}
				$(this).addClass("active");
				});
		</script>
		<script>

			var user = {{json_encode($user)}};
      var userId={{json_encode(\Cookie::get('userid'))}};
      var user_type = {{json_encode(\Cookie::get('user_type'))}};
      var session_timeout={{json_encode(\Cookie::get('session_timeout'))}};

			var oldToastr = toastr
			var toastr = {
				success: function (msg, title) {
					if (! title) title = 'Success'
					sweetAlert(title, msg, 'success')
				},
				error: function (msg, title) {
					if (! title) title = 'Error'
					sweetAlert(title, msg, 'error')
				},
				warning: function (msg, title) {
					if (! title) title = 'Info'
					sweetAlert(title, msg, 'warning')
				}
			}
      var mobileNo = {{\Cookie::get('mobileno')}};
		</script>

	<script>
      angular.module('DIPApp', ['isteven-multi-select','ngFileUpload'])
      .controller('MainCtrl', ['$scope', '$http', function ($scope, $http) {
        $scope.activeUserProfile = user;
        $scope.sessionExpired = setSessionFlag()

        $scope.updatedBalance = getUpdatedBalance()
				$scope.sessionExpired = false;

				$scope.resetSession = resetSession

				$scope.searchSender=searchSender;

				function setSessionFlag (user) {
          console.log(session_timeout)
					var d =  new Date(session_timeout);
					
					if (user_type == 2 || user_type == 3) return false
					if (user_type == 1) return  d.setMinutes(d.getMinutes() - 30)<= Date.now() ? true : false
				}

				function searchSender(sender){
					if ($scope.SearchSenderFrm.$invalid) return
					$http.post('/api/v1/actions/search/remitter', sender).then(data => {
                        console.log(data);
					//window.location.href= '/remitter/';

                    $http.post('/remitterdata', data).then(data => {
                        console.log(data);
                    //window.location.href= '/remitter/';

                    
                    }, searchfailed)


					}, searchfailed)
				}


				function resetSession () {
					$http.post('/api/v1/reset-bank-session', {'user_id': userId})
						.then(function (data) {
							window.location.reload();
						}, failed)
				}

                function getUpdatedBalance () {
                    $http.post('/api/v1/getupdatedbalance', {'user_id': userId})
                        .then(function (data) {
                            
                            $scope.wallectBalance=data.data.wallet_balance
                            
                        }, failed)
                }

				function failed (err) {
          if(err.data.code==1){
              $scope.logoutUser = true;
              $scope.sessionExpired = false;
          }
					//sweetAlert('Error', err.data.code, 'error')
				}

				function searchfailed (err)
				{sweetAlert('Error', err.data.message, 'error')
					//window.location.href= '/remitter/add';
				}

      }])

      .directive('isnumber', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isnumber = function (modelValue, viewValue) {
                if (! modelValue) return true;
                var regex = /^\d+$/g
                return regex.test(modelValue)
              }
            }
          }
        }])
      .directive('isphoneno', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isphoneno = function (modelValue, viewValue) {
                var regex = /^[0-9]+$/gi;
                if(! modelValue) return true;
                if (modelValue.length == 10 && regex.test(modelValue)) return true;
                return false;
              }
            }
          }
        }])
      .directive('isvalidpincode', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isvalidpincode = function (modelValue, viewValue) {
                var regex = /^[0-9]{6}$/gi;
                if(! modelValue) return true;
                if (regex.test(modelValue)) return true;
                return false;
              }
            }
          }
        }])
      .directive('ischar', [function() {
          return {
            require: 'ngModel',
            link: function(scope,elem,attrs,ctrl) {
            ctrl.$validators.ischar = function(val) {
              if (! val) return true;
                var regExp=/[A-Za-z]/g;
                return regExp.test(val);
              }
            }
          }
        }])
       .directive('isfloat', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isfloat = function (modelValue, viewValue) {
                if (! modelValue) return true;
                var regex = /^(?:[1-9]\d*|0)?(?:\.\d+)?$/
                return regex.test(modelValue)
              }
            }
          }
        }])
      .directive('fileModel', ['$parse', function ($parse) {
    return {
    restrict: 'A',
    link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });
    }
   };
}])
      .filter('dateToISO', function() 
        { return function(input) { return new Date(input).toISOString(); }; }
      )

      .filter('capitalize', function() {
          return function(input, scope) {
            if (input!=null)
            input = input.toLowerCase();
            return input.substring(0,1).toUpperCase()+input.substring(1);
          }
      })
      
      .service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(){
        })
        .error(function(){
        });
    }
}])
    </script>
    @section('javascript')
    @show
  </body>

</html>