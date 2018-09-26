<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="RefundCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			 <div class="panel panel-default">
			 	<!-- Page Title -->
			 	<div class="panel-heading">Refund</div>
			 	<!-- /Page Title -->
				<!-- Page Content -->
			 	<div class="tab-container ">
			 		<div class="tab-content ">
			 			 <!--<div class="panel panel-default panel-default panel-border-color panel-border-color-primary">-->
			 			 	   <div class="panel-body">
			 			 	   	<form name="RefundOtpFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="refundOtp(otp)" novalidate>
                                    <div class="form-group">
                                        <div class="col-sm-7 col-sm-offset-3">
                                            <div class="input-group">
                                                <div class="input-group-addon">Bank Txn ID</div>
                                                <input type="text" ng-model="otp.bankTaxid" name="bankTaxid" class="form-control err" placeholder="Enter Bank Txn ID" required>
                                                <div class="input-group-addon"><i class="mdi mdi-edit"></i></div>
                                                <button type="submit" class="btn btn-primary pull-right" >OTP</button>
                                            </div>
                                            <p ng-show="RefundOtpFrm.$submitted && RefundOtpFrm.bankTaxid.$invalid" class="err-mark">Please enter bank txn id.</p>
                                        </div>
                                    </div>
			 			 	   	<!--<div class="col-sm-6 col-sm-offset-3">
			 			 	   	   		<div class="row">
			 			 	   	   			<div class="form-inline">
												<div class="form-group">
													<div class="col-sm-12">
														<div class="input-group">
															<div class="input-group-addon">Bank Txn ID</div>
															<input type="text" ng-model="otp.bankTaxid" name="bankTaxid" class="form-control err" placeholder="Enter Bank Txn ID" required>
															<div class="input-group-addon"><i class="mdi mdi-edit"></i></div>
														</div>
														<button type="submit" class="btn btn-primary" >OTP</button>
														<p ng-show="RefundOtpFrm.$submitted && RefundOtpFrm.bankTaxid.$invalid" class="err-mark">Please enter bank txn id.</p>
													</div>
												</div>
											</div>
										</div>
									</div>-->
									</form>

									<div class="clearfix"></div>
			 			 	   	  <!-- Form -->
			 			 	   	   <form name="RefundFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="refundAmt(refund)" novalidate>
			 			 	   	   	
			 			 	   	   	<div class="form-group">
										<label class="col-sm-3 control-label" >Bank Txn ID</label>
										<div class="col-sm-9">
											<input type="text" ng-model="refund.txid" name="txid" class="form-control err" placeholder="Enter Bank Txn ID" required >
											<p ng-show="RefundFrm.$submitted && RefundFrm.txid.$invalid" class="err-mark">Please enter the Bank tax ID.</p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" >OTP</label>
										<div class="col-sm-9">
											<input type="text" ng-model="refund.otp" name="otp" class="form-control err" placeholder="Enter OTP" required isfloat>
											<p ng-show="RefundFrm.$submitted && RefundFrm.otp.$invalid" class="err-mark">Please enter the OTP.</p>
										</div>
									</div>
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <button type="submit" class="btn btn-primary" ng-click="">Validate</button>
                                            <a href="/" class="btn btn-primary">Back to Dashboard</a>
                                            <div class="clearfix"></div> <br>
                                            <a href="#">Get OTP</a>
                                            <p>If you have lost OTP</p>
                                        </div>
                                    </div>
			 			 	   	   </form>
			 			 	   	  <!-- /Form -->
			 			 	   	 </div>
<!--			 			 	   </div>-->
			 			 </div>
			 		</div>
			 	</div>
			 	<!-- /Page Content -->
			 </div>
		</div>
	</div>

<?php $domain_data = preg_replace('#^https?://#', '', Request::root());  ?>
  @if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' || $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in:8021' || $domain_data == 'dmt.ekiosk.in:8021' || $domain_data == 'dmt.aonehub.com:8021' || $domain_data == 'dmt.zippays.in:8021' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8021' || $domain_data == 'dmt.akpayments.in:8021' || $domain_data == 'dmt.oneindiapayments.com:8021')
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
	var bankTransactionId = "{{Input::get('bank_transaction_id')}}";
	//console.log(bankTransactionId);
	angular.module('DIPApp')
		.controller('RefundCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope
			jQuery(".loader").hide(0);
			$scope.refundAmt=refundAmt;
			$scope.refundOtp=refundOtp;
			bankTransactionId=bankTransactionId?bankTransactionId:''
			$scope.otp = {bankTaxid: bankTransactionId}
			$scope.refund = {txid: bankTransactionId}
			function refundAmt(refund)
			{

				
				if ($scope.RefundFrm.$invalid) return
					 jQuery(".loader").show(0);
					$http.post('/getRefundtransaction', refund).then(data => {
						jQuery(".loader").hide(0);
						sweetAlert('Success', 'Amount Added Successfully', 'success')
					location.reload();
				}, fail)
			}

			function refundOtp(otp)
			{
				if ($scope.RefundOtpFrm.$invalid) return
					 jQuery(".loader").show(0);
					$http.post('/getOtpRefund', otp).then(data => {
						jQuery(".loader").hide(0);
					sweetAlert('Success', 'OTP sent', 'success')
					location.reload();
				}, fail)
			}
			function fail (err) {
				jQuery(".loader").hide(0);
				sweetAlert('Error', err.data.message, 'error')
			}
	}])
</script>
@stop