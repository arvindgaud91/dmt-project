<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider"><h4>Commission Reports</h4><!--<span class="panel-subtitle">Snapshot</span>--></div>
				<!--<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default panel-table">-->
								
								<div class="panel-body">
									<table class="table table-striped table-bordered" >
										<thead>
											<tr>
												<th>Transaction ID</th>
												<th>Transaction Date</th>
												<th>Service</th>
												<th>Agent Name</th>
												<th>Amount</th>
												<th>Super Dist Name</th>
												<th>Super Dist Commission</th>
												<th>Dist Name</th>
												<th>Dist Commission</th>
												<th>Agent Name</th>
												<th>Agent Commission</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr>
												<td>Transaction ID</td>
												<td>Transaction Date</td>
												<td>Service</td>
												<td>Agent Name</td>
												<td>Amount</td>
												<td>Super Dist Name</td>
												<td>Super Dist Commission</td>
												<td>Dist Name</td>
												<td>Dist Commission</td>
												<td>Agent Name</td>
												<td>Agent Commission</td>
											</tr>
										</tbody>
									</table>
									<h5>No transactions conducted yet. Click on AEPS to begin your first transaction.</h5>
								</div>
								
							<!--</div>
						</div>
					</div>-->
					<div class="col-xs-12 col-md-12">
						<div class="widget widget-calendar">
							<div id="calendar-widget"></div>
						</div>
					</div>
					<!-- End Row -->
<!--				</div>-->
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script>
	
</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', function ($scope, $http) {
	window.s = $scope;
	
	function fail (err) {
		sweetAlert('Error', 'Something went wrong', 'error');
	}
}])
</script>
@stop
