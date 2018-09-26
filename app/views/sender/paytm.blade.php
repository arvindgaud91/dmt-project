<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">Sender List</div>
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
													<th>Name</th>
													<th>IFSC Code</th>
													<th>Account Number</th>
												</tr>
											</thead>
											<tbody class="no-border-x">
												<tr>
													<td>@{{beneficiary.name}}</td>
													<td>@{{beneficiary.dmt_bank_branch.ifsc}}</td>
													<td>@{{beneficiary.account_number}}</td>
												</tr>
											</tbody>
										</table>
										<br>
										<form name="AddrequestFrm" method="post" action="/api/v1/transactions/paytm" style="border-radius: 0px;" class="group-border-dashed" >
										<div class="form-group col-md-4">
											<div class="row">
												<label class="control-label" >Amount</label>
												<input type="text"  class="form-control" name="TXN_AMOUNT" placeholder="Enter Amount" required >
												
												<div class="clearfix"></div> <br> 
												<input type="hidden" name="remitter_id" value="<?php echo $beneficiary->remitter_id ?>">
												<input type="hidden" name="beneficiary_id" value="<?php echo $beneficiary->id ?>">
												<button type="submit" class="btn btn-primary btn-lg">Confirm</button>
													
											</div>
										</div>
									</form>
<!-- 
									<form method="post" action="/api/v1/transactions/paytm">
		<table border="1">
			<tbody>
				<tr>
					<th>S.No</th>
					<th>Label</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>1</td>
					<td><label>ORDER_ID::*</label></td>
					<td><input id="ORDER_ID" tabindex="1" maxlength="20" ng-model="request.ORDER_ID" size="20"
						name="ORDER_ID" autocomplete="off"
						value="<?php //echo  "ORDS" . rand(10000,99999999)?>">
					</td>
				</tr>
				<tr>
					<td>2</td>
					<td><label>CUSTID ::*</label></td>
					<td><input id="CUST_ID" tabindex="2" maxlength="12"  size="12" name="CUST_ID" autocomplete="off"  ng-model="request.CUST_ID" ng-init="CUST_ID=beneficiary.id"></td>
				</tr>
				<tr>
					<td>3</td>
					<td><label>INDUSTRY_TYPE_ID ::*</label></td>
					<td><input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" ng-model="request.INDUSTRY_TYPE_ID" value="Retail"></td>
				</tr>
				<tr>
					<td>4</td>
					<td><label>Channel ::*</label></td>
					<td><input id="CHANNEL_ID" tabindex="4" maxlength="12"
						size="12" name="CHANNEL_ID" ng-model="request.CHANNEL_ID"  autocomplete="off" value="WEB">
					</td>
				</tr>
				<tr>
					<td>5</td>
					<td><label>txnAmount*</label></td>
					<td><input title="TXN_AMOUNT" tabindex="10"
						type="text" name="TXN_AMOUNT" ng-model="request.TXN_AMOUNT"
						value="">
					</td>
				</tr><input type="text" name="remitter_id" value="<?php echo $beneficiary->remitter_id ?>">
												<input type="text" name="beneficiary_id" value="<?php echo $beneficiary->id ?>">
				<tr>
					<td></td>
					<td></td>
					<td><input value="CheckOut" type="submit"	onclick=""></td>
				</tr>
			</tbody>
		</table>
		* - Mandatory Fields
	</form> -->
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
<?php $domain_data = preg_replace('#^https?://#', '', Request::root());  ?>
  @if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' ||  $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in:8021' || $domain_data == 'dmt.ekiosk.in:8021' || $domain_data == 'dmt.aonehub.com:8021' || $domain_data == 'dmt.zippays.in:8021' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8021' || $domain_data == 'dmt.akpayments.in:8021' || $domain_data == 'dmt.oneindiapayments.com:8021')
	<div class="loader" style="position: fixed;top: 0%;left: 0%;z-index: 5000;height: 100%;width: 100%;text-align: center;background: rgba(255,255,255,0.8);border: 1px solid #000;" >
		<div><br/><br/><br/><br/><br/><h3>Transaction Processing......</h3><br/><h4>PLEASE DO NOT REFRESH THE PAGE.</h4></div><br />
	</div>
  @else
<div class="loader" style="position: fixed;top: 0%;left: 0%;z-index: 5000;height: 100%;width: 100%;text-align: center;background: rgba(255,255,255,0.8);border: 1px solid #000;" >
<img src="/images/cinqueterre.png" >
	<div><br/><br/><br/><br/><br/><h3>Transaction Processing......</h3><br/><h4>PLEASE DO NOT REFRESH THE PAGE.</h4></div><br />
</div>
  @endif
@stop
@section('javascript')


<script>
	angular.module('DIPApp')
		.controller('SenderListCtrl', ['$scope', '$http', function ($scope, $http) {
			window.s = $scope
			jQuery(".loader").hide(0);
			$scope.transactionsprocess=false;
			$scope.beneficiary = {{$beneficiary}}
			$scope.remitter_id ={{$remitter_id}}

			$scope.request = {
				'remitter_id': $scope.remitter_id,
				'beneficiary_id': $scope.beneficiary
			}

			
			function fail (err) {
				 $scope.transactionsprocess=false;
				jQuery(".loader").hide(0);
				sweetAlert('Error', 'Something went wrong', 'error')
			}

			function tranfail (err) {
				 $scope.transactionsprocess=false;
				jQuery(".loader").hide(0);
				sweetAlert('Error', err.data.message, 'error')
			}
	}])
</script>
@stop