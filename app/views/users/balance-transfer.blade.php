<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<!-- Page Title -->
				<div class="panel-heading">Balance Transfer</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content ">
						<div class="panel">
							<div class="panel-body">
								<div class="panel-body table-responsive">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>DIG CODE</th>
												<th>Amount</th>
												<th>Action</th>
												
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr ng-repeat="data in datas">
												<td>@{{data.user_name}}</td>
												<td>@{{data.balance}}</td>
												<td ng-if="data.status == 0">
													<button id="approve_transfer" class="btn btn-success btn-sm" ng-disabled="isDisabled" ng-click="approveBalance(data.user_name, data.id)">Approve</button>
												</td>
												<td ng-if="data.status == 1">
													Received from old portal to new portal...
												</td>
											</tr>
										</tbody>
									</table>
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
<script>
	angular.module('DIPApp')
		.controller('SenderListCtrl', ['$scope', '$http', function ($scope, $http) {
			window.s = $scope

			$scope.datas = {{json_encode($datas)}}

			console.log($scope.datas)
			// $http.post(' ', sendorObj).then(data => {
			// 		console.log('sent sucessfully')
			// 	}, fail)
			$scope.approveBalance = approveBalance
			 $scope.isDisabled = false;
			function approveBalance(user_name, id){
				$scope.isDisabled = true;
				$http.post('/balance-update/'+user_name+'/'+id)
		        .then(data => {
		          sweetAlert('Success', 'Balance Updated Successfully!')
		           setTimeout(function () {
		               location.href = window.location
		            }, 2000)
		        },
          		fail ) 
			}

			function fail (err) {
	          console.log(err.status)
	          if(err.status==401){
	            sweetAlert('Error', 'You are already transferred the amount from old portal to new portal!', 'error');
	            setTimeout(function () {
		               location.href = window.location
		            }, 2000)
	          }else{
	            sweetAlert('Error', 'Something Is Wrong.', 'error')
	          }
	      	}

	}])
</script>
@stop