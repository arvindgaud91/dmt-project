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
						 @if (Session::has('error'))
                               <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif
                  @if (Session::has('success'))
                               <div class="alert alert-success">{{ Session::get('success') }}</div>
                                @endif


							<div class="panel-body">
								<!-- Section Title -->
								<div class="panel-heading">Receiver OTP </div>
								<!-- /Section Title -->
								<!-- Form -->

								<?php

$responsedata=explode('-', $remitter_id);

if(isset($responsedata))
{

$remitterid = $responsedata[1];
$beneficiary =$responsedata[0];

}
								?>
								<form name="OtpReceiverFrm" method="post" action="/remitter/{{$beneficiary}}/deleted_otpbeneficiary" style="border-radius: 0px;" class="form-horizontal group-border-dashed"  novalidate>
									<div class="col-md-6">
										<div class="row">
											<div class="form-group">
												<label class="col-sm-3 control-label" >OTP</label>
												<div class="col-sm-9">
													<input type="text" name="otp" class="form-control err" placeholder="Enter otp" required>
													<input type="hidden" name="ben_id" value="<?php echo $beneficiary  ?>" class="form-control" >
												</div>
											</div>
											
										
										<button type="submit" class="btn btn-primary btn-lg">Submit</button>
										<button type="button" class="btn btn-warning btn-lg" ng-click="resendOtp({{$remitterid}},{{$beneficiary}})" >Resend OTP</button>
											
										</div>
									</div>
									
								</form>
								<!-- /Form -->

								 
							</div>
						</div>
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
			var id={{$remitter_id}};
			$scope.receiver = {}
			$scope.ifscVerified = false
			

			
			$scope.resendOtp = resendOtp


			function resendOtp (ben_id,remitterid) {
				

					 var filter1={
                 	     ben_id:ben_id,
						remitterid:remitterid,
						
                     }

				$http.post('/api/v1/beneficiary-delete-resendotp/'+ben_id+'-'+remitterid, filter1).then(data => {
					sweetAlert('Success', 'OTP Sent to Remitters Mobile No', 'success')
					
				}, fail)


			}
			function OTP(receiver){
				if ($scope.OtpReceiverFrm.$invalid) return

                 var filter1={
                 	     ben_id:ben_id,
						remitterid:remitterid,
						
                     }

				$http.post('/remitter/'+id+'/otpbeneficiary', filter1).then(data => {
					if(data.data.status==1)
					{
					sweetAlert('Success', 'Added Successfully', 'success')
					document.f11.submit();	
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