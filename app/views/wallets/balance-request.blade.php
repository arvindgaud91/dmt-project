<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
	<div ng-controller="WalletRequestCtrl" class="head-weight">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default panel-border-color panel-border-color-primary hpanel hgreen">
                    <div class="panel-heading panel-heading-divider"><h4 style="margin:0;">Wallet Credit Request</h4><!--<span class="panel-subtitle">Use to credit your wallet</span>--><p><small>Use to credit your wallet</small></p></div>

                    <div class="panel-body">
                    	<b style="color: red;">
    					We Are Not Accepting Any Deposited In KOTAK BANK LTD.(Cash Deposit / NEFT / IMPS)
    					</b>
    				</div>
					<div class="panel-body">
						<!-- Start row -->
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<div role="alert">
										<p><strong><small>ACCOUNT: DIGITAL INDIA PAYMENTS LIMITED</small></strong></p>
										<p><small>BANK : ICICI BANK LIMITED</small></p>
										<p><small>BRANCH : CIBD MUMBAI BRANCH</small></p>
										<p><small>ACCOUNT NO : 039305008196</small></p>
										<p><small>IFSC : ICIC0000393</small></p>
									</div>
								</div>
								
								<!-- <div class="col-md-6">
									<div role="alert">
										<p><strong><small>ACCOUNT: DIGITAL INDIA PAYMENTS LIMITED</small></strong></p>
										<p><small>BANK : KOTAK BANK LTD</small></p>
										<p><small>BRANCH : Powai</small></p>
										<p><small>ACCOUNT NO : 0112783976</small></p>
										<p><small>IFSC : KKBK0001399</small></p>
									</div>
								</div> -->
							</div>
						</div>
					</div>
					<!-- End row -->
                    </div>
			</div>
		</div>
        <div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<!--<div class="tools"><span class="icon mdi mdi-more-vert"></span></div>-->
									<div class="title">Wallet Credit Request Form</div>
								</div>
								<div class="panel-body table-responsive">
									<form class="form-signin" name="balanceRequestobjFrm" ng-submit="submit(balanceRequest)" novalidate>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
													<input type="text" ng-model="balanceRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isfloat>
													<p ng-show="balanceRequestobjFrm.$submitted && (balanceRequestobjFrm.amount.$invalid || invalidAmount(balanceRequest.amount))" class="err-mark">Please enter an amount between 100 and @{{maxRequestAmount}}.</p>

												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label" for="mode"><small>Select Mode of Transfer</small></label>
													<select ng-options="key as value for (key, value) in modeOfTransferDictS" ng-model="balanceRequest.transfer_mode" class="form-control" id="mode" name="mode" required>
														<option value="">SELECT OPTION</option>
													</select>
													<p ng-show="balanceRequestobjFrm.$submitted && balanceRequestobjFrm.mode.$invalid" class="err-mark">Please select a mode of transfer.</p>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label" for="bank"><small>Select Bank Account</small></label>
													<select ng-options="key as value for (key, value) in walletBanks" ng-model="balanceRequest.bank" class="form-control" id="bank" name="bank" required>
														<option value="">SELECT BANK</option>
													</select>
													<p ng-show="balanceRequestobjFrm.$submitted && balanceRequestobjFrm.bank.$invalid" class="err-mark">Please select a bank.</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label" for="branch"><small>Branch<font color="red"> * (Required)</font></small></label>
													<input type="text" ng-model="balanceRequest.branch" class="form-control" name="branch" placeholder="DEPOSIT BRANCH" required>
													<p ng-show="balanceRequestobjFrm.$submitted && balanceRequestobjFrm.branch.$invalid" class="err-mark">Please enter branch name.</p>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><small>Reference Number<font color="red"> * (Required)</font></small></label>
													<input type="text" class="form-control" ng-model="balanceRequest.reference_number" name="reference_number" placeholder="REFERENCE NUMBER" required>
													<p ng-show="balanceRequestobjFrm.$submitted && balanceRequestobjFrm.reference_number.$invalid" class="err-mark">Please enter the reference number.</p>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><small>Bank Receipt</small></label>
													<input type="file" class="form-control" name="bank_rec" file-model="myfile" placeholder="REFERENCE NUMBER" >
													<!-- <p ng-show="balanceRequestFrm.$submitted && balanceRequestFrm.bank_rec.$invalid" class="err-mark">Please enter the Bank Receipter.</p> -->
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<button type="submit" class="btn btn-success" ng-disabled="isDisabled" name="btn-credit-request" ><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT REQUEST</small></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
        <!-- End Row -->
	</div>
@stop

@section('javascript')
<script>
	angular.module('DIPApp')
		.controller('WalletRequestCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {

			window.s = $scope
			$scope.modeOfTransferDictS = {{ json_encode(Config::get('dictionary.MODE_OF_TRANSFER')) }}
      $scope.walletBanks = {{ json_encode(Config::get('dictionary.WALLET_BANKS')) }}

			$scope.submit = submit
			$scope.invalidAmount = invalidAmount
			var maxAmount=({{(int)Cookie::get('user_type')}} ==1) ? 800000:1500000
			$scope.maxRequestAmount=maxAmount

			function submit (balanceRequest) {
				
				if ($scope.balanceRequestobjFrm.$invalid || invalidAmount(balanceRequest.amount)) return
				$scope.isDisabled = true;
			var file=$scope.myfile;
				req = Object.assign(balanceRequest, {user_id: {{Cookie::get('userid')}} ,file:$scope.myfile})

				// $http.post('/api/v1/wallets/balance-requests', req)
				// .then(data => {
				// sweetAlert('Success', 'Wallet request has been sent', 'success')
    //       setTimeout(function () {
    //                     location.reload();
    //          }, 2000)
				// }, fail)

				 var submit=Upload.upload({
					   	url:'/api/v1/wallets/balance-requests',
					   	method:"post",
					   	data:req,
					   });
					   submit.then(function(response)
					   {

                          console.log(response);
                        if(response.status==200)
                        {
                          sweetAlert('Success', 'wallet Request  Submitted Successfully!!.', 'success')
			                setTimeout(function () {
			                  location.reload();
			                }, 1500)

                        }else{
                        sweetAlert('Error', 'Something Is Wrong.', 'error')
                        
                        
                        }
                  },fail);

			}

			function invalidAmount (amount) {

				return amount >= 100 && amount <= $scope.maxRequestAmount ? false : true
			}
			function fail (err) {
				sweetAlert('Error', err.data.message, 'error')
				//sweetAlert('Error', 'Something went wrong', 'error')
			}
		}])

</script>
@stop
