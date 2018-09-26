<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">Sender</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content ">
						<div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
							<div class="panel-body">
								<!-- Section Title -->
							
								<div class="panel-heading">Sender Details</div>
								<!-- /Section Title -->
								<div class="panel-body table-responsive">
									<table class="table table-striped table-borderless">
										<thead>
											<tr>
												<th>Remitter ID</th>
												<th>Sender Name</th>
											<th>Mobile Number</th>
												<th>City</th>
												<th>Pincode</th>
												<th>KYC status</th>
												<th>Limit</th>
												<th>Consumed limit</th>
												<th>Action</th>
												
												
											</tr>
										</thead>
										<tbody class="no-border-x">
										
											<tr>
												<td>@{{data.remitterdetail.remitterid}}</td>
												<td>@{{data.remitterdetail.remittername}}</td>
												<td>999999</td>
												<td>@{{data.remitterdetail.lcity}}</td>
												<td>@{{data.remitterdetail.lpincode}}</td>
												<td ng-if="data.remitterdetail.kycstatus=='7'">
													Approved
												</td>
												<td ng-if="data.remitterdetail.kycstatus!='7'">
													Not Approved
												</td>
												<td>@{{data.remitterdetail.remaininglimit}}</td>
												<td>@{{data.remitterdetail.consumedlimit}}</td>
												<td><a class="btn btn-primary btn-sm" ng-href="/remitter/@{{data.remitterdetail.remitterid}}/beneficiary/add"><i class="mdi mdi-plus"></i> ADD NEW RECEIVER</a></td>
												
												<!-- <td><a class="btn btn-primary btn-sm" ng-href="/kyc-form/@{{remitter.rbl_remitter_code}}">Apply Kyc</td> -->
											</tr>
											
										</tbody>
									</table>

								
								</div>
								

								<!-- Section Title  -->
								<div class="panel-heading" >Receiver Details</div>
								<!-- /Section Title -->
								<div class="panel-body table-responsive">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

@stop
@section('javascript')




<script>
	angular.module('DIPApp')
		.controller('SenderListCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope
			
			
			//console.log($scope.data)
			$scope.deleteBeneficiary = deleteBeneficiary
$scope.resendOtp = resendOtp

			function deleteBeneficiary (ben_id,remitterid) 
			{
				

				var filter1={
                 	     ben_id:ben_id,
						remitterid:remitterid,
						
                     }
				$http.post('/deleteBeneficiaryweb', filter1)
					.then(function (data) {		
					console.log(data);		
						sweetAlert('Success', 'OTP Send Successfully ', 'success')
						window.location.href = "/remitter/"+ben_id+'-'+remitterid+"/beneficiary/delete_otp"
						
					})




			}





			function resendOtp (remitterid,ben_id) {
				

					 var filter1={
                 	     ben_id:ben_id,
						remitterid:remitterid,
						
                     }

				$http.post('/api/v1/beneficiary-delete-resendotp/'+ben_id+'-'+remitterid, filter1).then(data => {
					sweetAlert('Success', 'OTP Sent to Remitters Mobile No', 'success')
				}, fail)


			}

			function fail (err) {
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop