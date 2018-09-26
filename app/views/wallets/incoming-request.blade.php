<?php use Acme\Auth\Auth;
	$user = Auth::user();
?>
@extends('layouts.master')
@section('content')
<div ng-controller="RequestCtrl" class="head-weight">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-border-color panel-border-color-primary">
			<div class="panel-heading panel-heading-divider">
			<div class="row">
				<div class="col-md-8">
					<h3>Incoming Request</h3>
				</div>
				<div class="col-md-4 form-group">
					<!-- <select class="form-control" ng-model="requestType" ng-change="fetchRequests(requestType)">
						<option value=0 >Pending</option>
						<option value=1>Accepted</option>
						<option value=2>Rejected</option>
					</select> -->
				</div>
			</div>
			</div>
			<div class="panel-body">
				<form  style="border-radius: 0px;" class="form-horizontal group-border-dashed">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>ID</th>
											<th>DATE</th>
										<th>AGENTS</th>
										<th>AMOUNT</th>
										<th>REMARK</th>

										<th>ACTION</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="request in requests">
										<td>@{{request.userid}}</td>
											<td>@{{request.requested_on}}</td>
										<td>@{{request.user_name}}</td>
										<td>@{{request.req_amount}}</td>
										<td>@{{request.remarks}}</td>
										<td ng-show="request.req_status == 'P'">
											<button ng-click="approve(request, $index)" ng-disabled="isDisabled" class="btn btn-xs btn-success">Approve</button>
											<button ng-click="reject(request, $index)" ng-disabled="isDisabled" class="btn btn-xs btn-danger">Reject</button>
										</td>
										<td ng-show="request.req_status == 'A'">
											
										<button  class="btn btn-xs btn-info">Approved</button>
										</td>
										<td ng-show="request.req_status == 'R'">
											
										<button  class="btn btn-xs btn-warning">Rejected</button>
										</td>
										<!-- <td>311</td>
										<td>HEMANT P DUMPRE</td>
										<td>20</td>
										<td>Test</td>
										<td ng-show="requestType == 0">
											<button ng-click="approve(request, $index)" ng-disabled="isDisabled" class="btn btn-xs btn-success">Approve</button>
											<button ng-click="reject(request, $index)" ng-disabled="isDisabled" class="btn btn-xs btn-danger">Reject</button>
										</td> -->
									</tr>
								</tbody>
							</table>
							 <ul class="pagination"  ng-hide="pagination_buttons.length<1">
					            <li ng-show="current_page>1">
					                <a href="/transaction-reports?page=1">First</a>
					            </li>
					            <li ng-repeat="i in pagination_buttons" ng-class="{true: 'active', false: ''}[current_page == i]">
					              <a href="/transaction-reports?page=@{{ i}}">@{{ i}}</a>
					            </li>
					            <li  class="disabled" ng-show="button_number>button_to_show && (last_page-current_page)>=button_to_show">
					              <a href="">.......</a>
					            </li>
					            <li ng-show="button_number>=10 && (last_page-current_page)>9">
					              <a href="/transaction-reports?page=@{{ last_page}}">Last</a>
					            </li>

					                            <!--li class="active"><a href="#">@{{ pagination_buttons}}</a></li-->

					            </ul>
					</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script>
	angular.module('DIPApp')
		.controller('RequestCtrl', ['$scope', '$http', function ($scope, $http) {
		window.s = $scope;
		$scope.requestType = '0';
		$scope.requests = {{ json_encode($requests) }};
		console.log($scope.requests)
		$scope.approve = approve
		$scope.reject = reject;
		$scope.checkBalance = checkBalance;
		$scope.type = $scope.activeUserProfile.vendorDetails.type;
		$scope.fetchRequests = fetchRequests;
		function fetchRequests (rt) {
				var status = rt == 1 ? 'approved' : rt == 2 ? 'rejected' : 'pending'
				$http.get('/api/v1/users/'+{{Cookie::get('userid')}}+'/actions/incoming-balance-requests?status=${ status }')
				.then(data => {
							$scope.requests = data.data
							console.log($scope.request);
					}, fail)
			}

		 function checkBalance (amt) {
			return $scope.availBalance < amt ? false : true
		}
		
		function approve(request, index) {
			if(! confirm('Are you sure?')) return;
			$scope.isDisabled = true;
			// if (! checkBalance(request.amount)) return sweetAlert('Error', 'Insufficient Balance', 'error')
			
			// var acceptUrl = $scope.type == 3 ?  `/api/v1/wallets/balance-requests/8/from-super-distributors/actions/approve` : $scope.type == 2 ?  `/api/v1/wallets/balance-requests/8/from-distributors/actions/approve` : ''
			$http.post(`/api/v1/wallets/balance-requests/${request.request_id}/from-distributors/actions/approve`)
			.then(function(data){
					$scope.requests.splice(index, 1);
					toastr.success('Approved');
					setTimeout(function () {
  	            location.reload()
             }, 2000)
			}, fail);
		};


		/* code for pagination*/
var per_page=100;
var button_to_show=10;
 var button_number;
   var total_button={{$total }};
   button_number={{$total }};
   var current_page={{$current_page }};;
   var last_page=Math.ceil({{$total }}/per_page);

   if(button_number>button_to_show){
    if((last_page-current_page)>=button_to_show){
        button_number=current_page+button_to_show;
    }else{
        button_number=last_page;
    }
}
	$scope.button_to_show=button_to_show

	$scope.button_number=button_number
    $scope.current_page=current_page
    $scope.per_page=per_page
    $scope.last_page=last_page
    var pagination_buttons = []
    
    current_page=current_page>9?(current_page-5):current_page
    for(var i=current_page;i<=button_number;i++) {
        pagination_buttons.push(i)
   	}
    $scope.pagination_buttons=pagination_buttons
/*end */

		function reject (request, index) {
			 if(! confirm('Are you sure?')) return;
			 $scope.isDisabled = true;

			$http.post(`/api/v1/wallets/balance-requests/${request.request_id}/from-distributors/actions/reject`)
			.then(function(data){
					$scope.requests.splice(index, 1)
					toastr.success('Rejected');
					setTimeout(function () {
  	            location.reload()
             }, 2000)
			}, fail);
		}
		function fail (err) {
				sweetAlert('Error', 'Your Wallet Having Insifficient Balance.', 'error')
			}
		}])
</script>
@stop