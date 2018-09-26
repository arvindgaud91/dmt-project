<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AddSenderCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			 <div class="panel panel-default">
			 	<!-- Page Title -->
			 	<div class="panel-heading">Search Sender</div>
			 	<!-- /Page Title -->
				<!-- Page Content -->
			 	<div class="tab-container ">
			 		<div class="tab-content ">
			 			 <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
			 			 	   <div class="panel-body">
			 			 	   	  <!-- Search Form -->
			 			 	   	   <form name="SearchSenderFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="searchSender(sender)" novalidate>
			 			 	   	   	<div class="form-inline">
										<div class="form-group">
											<label class="col-sm-3 control-label" >Mobile</label>
											<div class="col-sm-9">
												<div class="input-group">
													<div class="input-group-addon"><i class="mdi mdi-search"></i></div>
													<input type="text" ng-model="sender.mobile" name="mobile" class="form-control err" placeholder="Enter Mobile" required isphoneno>
													<div class="input-group-addon"><i class="mdi mdi-edit"></i></div>
												</div>
												<p ng-show="SearchSenderFrm.$submitted && SearchSenderFrm.mobile.$invalid" class="err-mark">Please enter a mobile no.</p>
											</div>
										</div>
									</div>
									<button type="submit" class="btn btn-primary btn-lg">Search</button>
			 			 	   	   </form>
			 			 	   	  <!-- /Search Form -->
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
		.controller('AddSenderCtrl', ['$scope', '$http', function ($scope, $http) {
			window.s = $scope
			$scope.searchSender=searchSender;
			function searchSender(sender){
				if ($scope.SearchSenderFrm.$invalid) return
				$http.post('/api/v1/actions/search/remitter', sender).then(data => {
					window.location.href = "/remitters"
				}, fail)
			}
			function fail (err) {
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop