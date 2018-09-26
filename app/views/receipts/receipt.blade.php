<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-6 col-md-offset-3"  id="section-to-print">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					Transaction  Receipt <i style="float: right; cursor: pointer;" class="icon mdi mdi-print" onclick="printReceipt('section-to-print');"></i>
					<div class="clearfix"></div>
				</div>
				<?php $domain_data = preg_replace('#^https?://#', '', Request::root());?>
				@if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' || $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in:8021' || $domain_data == 'dmt.ekiosk.in:8021' || $domain_data == 'dmt.aonehub.com:8021' || $domain_data == 'dmt.zippays.in:8021' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8021' || $domain_data == 'dmt.akpayments.in:8021' || $domain_data == 'dmt.oneindiapayments.com:8021')
				<img src="/images/_blank.png" height="50px;" width="50px;" style="margin-left: 20px;">
				@else
				<img src="/images/_blank.png" height="50px;" width="50px;" style="margin-left: 20px;">
				@endif
				<!-- <img src="/images/rbl.png" align="right" height="50px;" style="margin-right:15px;"> -->
				<div class="panel-body">
					<form style="border-radius: 0px;" class="form-horizontal group-border-dashed">
						<table class="table table-bordered">
							<thead>
								
							</thead>
							<tbody>
								<tr>
									<td>Transaction ID:</td>
									<td>@{{transactions.transactionIds}}</td>
								</tr>
								<tr>
									<td>Date &amp; Time Of Transaction:</td>
									<td>@{{transactions.transaction_on | date: 'medium'}}</td>
								</tr>
								<tr>
									<td>Sender Name:</td>
									<td>@{{transactions.remitter_name}}</td>
								</tr>
								<tr>
									<td>Sender Mobile: </td>
									<td>@{{transactions.remitter_mobile}}</td>
								</tr>
								<tr>
									<td>Beneficiary Name: </td>
									<td>@{{transactions.bene_name}}</td>
								</tr>
								<tr>
									<td>IFSC Code: </td>
									<td>@{{transactions.ifsc_code}}</td>
								</tr>
								<tr>
									<td>Account Number: </td>
									<td>@{{transactions.account_no}}</td>
								</tr>
								<tr>
									<td>Transaction Amount: </td>
									<td>@{{transactions.tran_amount}}</td>
								</tr>
								<tr>
									<td>Transaction Status: </td>
									<td>@{{transactions.tran_status}}

									</td>
								</tr>
								<tr>
									<td>Transaction Description: </td>
									<td>@if(stristr($transactions->tran_desc, 'Insufficient Avail Bal'))
										BANK NODE OFFLINE
										@else
									@{{transactions.tran_desc}}
										@endif
									</td>
								</tr>
								<tr>
									<td>BC Agent Name: </td>
									<td>@{{transactions.bc_agent}}</td>
								</tr>
							</tbody>
						</table>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script type = "text/javascript" >

  history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

</script>
<script>
	var transactions = {{json_encode($transactions) }}
console.log(transactions)
    
</script>
<script language="javascript">
function printReceipt(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = document.all.item(printpage).innerHTML;
var oldstr = document.body.innerHTML;
document.body.innerHTML = headstr+newstr+footstr;
window.print();
document.body.innerHTML = oldstr;
return false;
}
</script>
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
	window.s = $scope
	 $scope.transactions = transactions
	// $scope.exportFile = exportFile

	// function formatTransaction (tx) {
	// 	tx.created_at = new Date(tx.created_at)
	// 	return tx
	// }
		function fail (err) {
		sweetAlert('Error', 'Something went wrong', 'error')
	}
}])
</script>
@stop