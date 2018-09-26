<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="OtpReceiverCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">OTP </div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content">
						<div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
							<div class="panel-body">
								<!-- Section Title -->
								<div class="panel-heading">Receiver OTP </div>
								<!-- /Section Title -->
								<!-- Form -->
								 @if (Session::has('error'))
                               <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif
                                 @if (Session::has('success'))
                               <div class="alert alert-success">{{ Session::get('success') }}</div>
                                @endif
                                <?php

$responsedata=explode('-', $remitter_id);

if(isset($responsedata))
{

$remitterid = $responsedata[1];
$beneficiary =$responsedata[0];

}
								?>
								<form name="OtpReceiverFrm"  style="border-radius: 0px;" ng-submit="otpbeneficiary(sender)"  class="form-horizontal group-border-dashed"  >
									<div class="col-md-6">
										<div class="row">
											<div class="form-group">
												<label class="col-sm-3 control-label" >OTP</label>
												<div class="col-sm-9">
													
													<input type="text" ng-model="sender.otp" name="otp" class="form-control err" placeholder="Enter Otp" required>
													<p ng-show="OtpReceiverFrm.$submitted && OtpReceiverFrm.otp.$invalid" class="err-mark">Please enter the Name.</p>
													
												</div>
											</div>											
										<button type="submit" class="btn btn-primary btn-lg">Submit</button>
											
										</div>
									</div>
								</form>
								<!-- /Form -->
								<div class="col-md-6">
                                       <?php
									echo '<a href="/remitter/'.$remitter_id.'/beneficiary/beneficiary_otpresend_ben_otp_link" class="btn btn-primary btn-lg" style="float: right;">Resend otp</a>';
									?>

									
								</div>
							</div>
						</div>

						 <form  method="post"  id="myForm" class="myForm" name="f11" style="width: 460px;" action="/api/v1/actions/search/remitter">
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone_no" maxlength="10" id="phoneNoo"  pattern="[0-9]{10}"  pattern="\d*" title="10 Digit mobile number." placeholder="Search Remitter" value="<?php echo $phone_no ?>" style="display:  inline-block; width: 180px; float:  left;"  maxlength="10" required>

                        
                        <input style="display:  inline-block; float: left; margin-top: 10px;" type="submit" class="btn btn-success btn-sm" value="Search">
                       
                    </div>
                </form>
					</div>
				</div>
			</div>
			<!-- /Page Content -->
		</div>
	</div>
</div>
</div>

@stop
@section('javascript')

<script type="text/javascript">
	$("#myForm").hide();
</script>
<script src="http://4review.firstquadrant.co.in/firstpay/public/js/ng-file-upload-shim.js"></script>	
	<script src="http://4review.firstquadrant.co.in/firstpay/public/js/ng-file-upload-shim.min.js"></script>	
	<script src="http://4review.firstquadrant.co.in/firstpay/public/js/ng-file-upload.min.js"></script>
<script>
	angular.module('DIPApp')
		.controller('OtpReceiverCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope;
			var id={{$remitterid}};
			var ben={{$beneficiary}}
			var phone_no={{$phone_no}} 
			$scope.receiver = {}
			$scope.ifscVerified = false
			$scope.otpbeneficiary =otpbeneficiary

			
			
			function otpbeneficiary(receiver){
			
				if ($scope.OtpReceiverFrm.$invalid) return

				$http.post('/remitter/'+id+'/'+ben+'/'+phone_no+'/otpbeneficiary', receiver).then(data => 
				{

		          if(data.data.status==1)
		           {
		                   document.f11.submit();
		                   sweetAlert('Success', 'Added Successfully', 'success');
		           }else
		           {
		           	sweetAlert('Error', 'OTP Not Match Please check', 'error');
		           }
					
					
				}, fail)
			}

			function customRequiredCheck (model, key) {
				if (! key)
				return (! model || model.length == 0) ? false : true
				return (! _.has(model, key) || model[key] == "") ? false : true
			}

			function fail (err) {
				if (err.data.code)
					return sweetAlert('Error', err.data.message, 'error')
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop