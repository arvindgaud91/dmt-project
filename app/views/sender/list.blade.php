<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<!-- Page Title -->
				<div class="panel-heading">Sender List</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content ">
						<div class="panel">
							<div class="panel-body">
								<!-- Section Title -->
                                <div class="panel-heading text-center"><strong style="font-size: 15px;">Sender Details</strong></div>
								<!-- /Section Title -->
								<div class="panel-body table-responsive">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Remitter ID</th>
												<th>Sender Name</th>
												<th>Contact</th>
												<th>City</th>
												<th>Pincode</th>
												<th>KYC status</th>
												<th>Remaining Limit</th>
												<!-- <th>Action</th>
												 -->
											</tr>
										</thead>
										
										<tbody class="no-border-x">
											<tr ng-repeat="remitter in remitters">
												<td><a style="color: blue;" href="/api/v1/actions/search/remitter?phone_no=@{{remitter.remittermobilenumber}}"><!-- <a href="remitter/@{{remitter.remitterid}}"> -->@{{remitter.remitter_code}}</a></td>
												
												<td>@{{remitter.remittername}}</td>
												<td>@{{remitter.remittermobilenumber}}</td>
												<td>@{{remitter.cityname}}</td>
												<td>@{{remitter.pincode}}</td>
												<td ng-if="remitter.kycstatus=='0'">KYC NOT DONE</td>
												<td ng-if="remitter.kycstatus=='1'">KYC DONE</td>
												<td>@{{remitter.remaininglimit}}</td>
												<!-- <td><a class="btn btn-primary btn-sm" ng-href="/remitter/@{{remitter.remitterid}}/beneficiary/add"><i class="mdi mdi-plus"></i> ADD NEW RECEIVER</a></td> -->
											</tr>
										</tbody>
									</table>
									<!-- /Form -->
								</div>

								<!-- Section Title --><!-- 
								<div class="panel-heading">Receiver Details</div> -->
								<!-- /Section Title -->
								<!-- <div class="panel-body table-responsive">
									<table class="table table-striped table-borderless">
										<thead>
											<tr>
												<th>Beneficiary ID</th>
												<th>Receiver Name</th>
												<th>Amount</th>
												<th>IFSC</th>
												<th>Registered</th>
												<th>IMPS</th>
												<th>NEFT</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr>
												<th>Beneficiary ID</th>
												<th>Receiver Name</th>
												<th>Amount</th>
												<th>IFSC</th>
												<th>Registered</th>
												<th>IMPS</th>
												<th>NEFT</th>
												<th><button type="button" class="btn btn-primary btn-sm" ng-click=""><i class="mdi mdi-minus"></i> DELETE</button></th>
											</tr>
										</tbody>
									</table>
								</div> -->
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


<script>
	angular.module('DIPApp')
		.controller('SenderListCtrl', ['$scope', '$http', function ($scope, $http) {
			window.s = $scope

			$scope.remitters = {{json_encode($data)}}
			console.log($scope.remitters)
			// $http.post(' ', sendorObj).then(data => {
			// 		console.log('sent sucessfully')
			// 	}, fail)
			function fail (err) {
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop