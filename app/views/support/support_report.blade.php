<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider"><h4>Support Reports</h4><!--<span class="panel-subtitle">Snapshot</span>--></div>
				<div class="panel-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="panel-default panel-table">
								<div class="panel-heading">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
									<!--<div class="title">Search <input type="text" ng-model="search"></div>
									<br>-->
									<div class="row">
                                        <div class="col-md-1" style="padding-top:5px;">Search</div>
                                        <div class="col-md-3">
                                            <input class="form-control input-sm" type="text" ng-model="search">
                                        </div>
                                    </div>
								</div>
								<div class="table-responsive">
                                                                    
									<!-- <table ng-show="transactions.length > 0" class="table table-striped table-borderless"> -->
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Ticket ID</th>
												<th>Type</th>
												<th>Message</th>
												
												<th>Status</th>
												
												<th>Ticket Date</th>
                                                                                                <th>Response</th>
												<th>Response Date</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr ng-repeat="tx in support_data |  filter:search">
												<td>@{{tx.ticket_id}}</td>
												<td>@{{tx.support_name }}</td>
												<td>@{{tx.message}}</td>
												<td><lable class="" style="color: green;">@{{tx.status}}</lable></td>
												
												<td>@{{tx.created_at | date:"dd/MM/yyyy 'at' h:mma" }}</td>
                                                                                                <td>@{{tx.response}}</td>
												<td>@{{tx.response_date | date:"dd/MM/yyyy 'at' h:mma" }}</td>
												
											</tr>
										</tbody>
									</table>
									<?php Paginator::setPageName('page'); ?>
									{{ $support_data->appends(getAppendData())->links() }}
									<!-- <h5 ng-hide="transactions.length > 0">No transactions conducted yet. Click on AEPS to begin your first transaction.</h5> -->
									<?php
										function getAppendData ()
										{
											return [];
										}
									?>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="col-xs-12 col-md-12">
						<div class="widget widget-calendar">
							<div id="calendar-widget"></div>
						</div>
					</div> -->
					<!-- End Row -->
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script>
	var support_data = {{ json_encode($support_data->getItems()) }};
	
</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	window.s = $scope
	
	$scope.support_data = support_data.map(formatAdminTransaction)
	var statusDict = {0: 'Pending', 1: 'Approved', 2: 'Rejected'}
	
	
	function formatAdminTransaction (tx) {
                tx.ticket_id = tx.ticket_id
                tx.type = tx.support_name
                tx.message = tx.message
                tx.status = tx.status
		tx.created_at = new Date(tx.created_at)
		tx.response = tx.response!=""? tx.response: "--"
                tx.response_date = tx.response_date!=""? new Date(tx.response_date) : "--"
                
		
		return tx
	}
	/*function formatVendorTransaction (tx) {
		tx.created_at = new Date(tx.created_at)
		tx.transaction_id = tx.debit ? tx.debit.id : (tx.credit && tx.credit.id ? tx.credit.id : null)
		tx.service = tx.debit ? 'Debit' : (tx.credit && tx.credit.id ? 'Credit' : null)
		tx.amount = tx.debit ? tx.debit.amount : (tx.credit && tx.credit.id ? tx.credit.amount : null)
		tx.balance = tx.debit ? tx.debit.balance : (tx.credit && tx.credit.id ? tx.credit.balance : null)
		return tx
	}*/
            /*
	function exportFile (filename, data) {
	  $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
	  	if (filename == 'vendor-transactions') {
	    	newObj = {
	    		'Wallet_Transaction_ID': obj.id,
	    		'Transaction_Date': $filter('date')(obj.created_at, 'medium'),
	    		'Service': obj.service,
	    		'Amount': obj.amount,
	    		'Balance': obj.balance
	    	}
	    }
	    if (filename == 'admin-transactions') {
	    	newObj = {
	    		'Wallet_Transaction_ID': obj.id,
	    		'Transaction_Date': $filter('date')(obj.created_at, 'medium'),
	    		'Amount': obj.amount,
	    		'Bank': obj.bank,
	    		'Branch': obj.branch,
	    		'Mode': obj.transfer_mode,
	    		'Ref_No': obj.reference_number,
	    		'Status': obj.status_label
	    	}
	    }
	    return newObj
	  })}).then(function (data) {
	    window.location.href = '/exports/'+data.data+'.xls'
	  }, console.log)
	}
*/
	function fail (err) {
		console.log(err);
		sweetAlert('Error', 'Something went wrong', 'error');
	}
}])
</script>
@stop