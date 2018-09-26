<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">Transaction Reports<span class="panel-subtitle">Snapshot</span></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default panel-table">
								<!-- <div class="panel-heading">
									 <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> 
									 <div class="title">Last Five Transactions</div>
								</div> -->
								<div class="panel-body table-responsive">
									<div class="title">Search <input type="text" ng-model="search"></div>
                              <br>
									<table class="table table-striped table-borderless">
										<thead>
											<tr>
												<th>Bank Transaction Id</th>
												<th>Bank Remarks</th>
												<th>Reference No</th>
												<th>Transaction Date</th>
												<th>Sender Name</th>
												<th>Mobile</th>
												<th>Beneficiary Name</th>
												<th>Beneficiary Account No</th>
												<th>Total Amount</th>
												<th>Status</th>
												
												<th>Receipt</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr ng-repeat="transaction in transactions |  filter:search">
												<td>@{{transaction.bank_transaction_id}}</td>
												<td>@{{transaction.remarks}}</td>
												<td>@{{transaction.reference_number}}</td>
												<td>@{{transaction.created_at | date: 'medium'}}</td>
												<td>@{{transaction.remitter.name}}</td>
												<td>@{{transaction.phone_no}}</td>
												<td>@{{transaction.beneficiary.name}}</td>
												<td>@{{transaction.beneficiary.account_number}}</td>
												<td>@{{transaction.amount | currency: 'Rs. '}}</td>
												<td>@{{transaction.status}}</td>
												
												<td><a class="btn btn-primary" type="submit" ng-href="/receipts/@{{transaction.transaction_group_id}}">Receipt</a></td>
											</tr>
										</tbody>
									</table>
									<?php Paginator::setPageName('page'); ?>
									{{ $transactionsObj->appends(getAppendData())->links() }}
									<?php
										function getAppendData ()
										{
											return [];
										}
									?>
									<h5 ng-hide="transactions.length > 0">No transactions conducted yet.</h5>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-12">
						<div class="widget widget-calendar">
							<div id="calendar-widget"></div>
						</div>
					</div>
					<!-- End Row -->
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>>
<script>
$('.input-daterange input').each(function(){
	$(this).datepicker('clearDates');
});
	var transactions = {{json_encode($transactions) }}

</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	window.s = $scope
	$scope.transactions = transactions
	// $scope.exportFile = exportFile
	console.log($scope.transactions)

	// function formatTransaction (tx) {
	// 	tx.created_at = new Date(tx.created_at)
	// 	return tx
	// }

	// function exportFile (filename, data) {
	//   $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
  	
 //    	newObj = {
 //    		'Transaction_ID': obj.tx_id,
 //    		'Transaction_Date': $filter('date')(obj.tx_date, 'medium'),
 //    		'Service': obj.service ,
 //    		'Aadhaar_No': obj.aadhar_no ,
 //    		'Bank': obj.bank_name,
 //    		'RRN Number': obj.rrn_no,
 //    		'Amount': obj.amount,
 //    		'Commission_Amount': obj.commission_amount,
 //    		'Bank_Balance': obj.balance,
 //    		'Wallet_Balance': obj.wallet_balance,
 //    		'Status': obj.status,
 //    		'Remark': obj.remarks
 //    	}
	    
	//     return newObj
	//   })}).then(function (data) {
	//     window.location.href = '/exports/'+data.data+'.xls'
	//   }, console.log)
	// }


	function fail (err) {
		console.log(err)
		sweetAlert('Error', 'Something went wrong', 'error')
	}
}])
</script>
@stop