<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AddReceiverCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">Add Receiver</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content">
						<div class="panel-default panel-default panel-border-color panel-border-color-primary">
							<div class="panel-body">
								<!-- Section Title -->
                                <div class="row">
                                    <div class="col-md-6 col-sm-offset-3">
                                        <div class="panel-heading"><strong style="font-size: 15px;">Receiver Details</strong></div>
                                    </div>
                                </div>
								<!-- /Section Title -->
								<!-- Form -->
								<form name="AddReceiverFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="registerReceiver(receiver)" novalidate>
									<div class="col-md-6">
										<div class="row">
											<div class="form-group">
												<label class="col-sm-3 control-label" >Beneficiary Name</label>
												<div class="col-sm-9">
													<input type="text" ng-model="receiver.name" name="name" class="form-control err" placeholder="Enter Beneficiary Name" required ischar>


													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.name.$invalid" class="err-mark">Please enter the beneficiary name.</p>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-sm-3 control-label" >Account Number</label>
												<div class="col-sm-9">
													<input type="text" ng-model="receiver.account_number" name="accountNo" class="form-control err" placeholder="Enter Account Number" required isnumber>
													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.accountNo.$invalid" class="err-mark">Please enter account number.</p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Bank</label>
												<div class="col-sm-9">
												<select name="bankName" id="BankName" class="form-control err" ng-model="receiver.bankName" ng-change="masterIfsc(receiver.bankName)">
													<option value="">Select Bank</option>
	                          						<option value=@{{bank.dp_bank_id}} ng-repeat="bank in banks">@{{bank.bank_name}}</option>
	                          
	                        					</select>
	                        					</div>
												<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.bankName.$invalid" class="err-mark">Please select bank.</p>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label" >IFSC</label>
												<div class="col-sm-6">
													<input type="text" ng-model="bank_ifsc" name="ifsc" class="form-control err" placeholder="Enter IFSC" ng-disabled = "ifscVerified" required>
													<!-- <p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.ifsc.$invalid" class="err-mark">Please enter the ifsc.</p>
													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.ifsc.$valid && ! ifscVerified" class="err-mark">Please verify the entered ifsc.</p> -->
												</div>
												<div class="col-sm-3">
													<button type="button" style="margin-top: 7px;" class="btn btn-primary btn-md" ng-click="ifscLookUp(bank_ifsc,receiver.bankName)" ng-hide="ifscVerified">Search</button>
													<button type="button" style="margin-top: 7px;" class="btn btn-primary btn-md" ng-click="resetIfsc(bank_ifsc)" ng-show="ifscVerified">Change</button>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label" >Branch</label>
												<div class="col-sm-9">
													<input type="text" ng-model="bank_branch" name="branchName" class="form-control err" disabled required>
													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.branchName.$invalid" class="err-mark">Please Enter Branch Name.</p>
												</div>
											</div>

											
											<div class="form-group">
												<div class="col-sm-3" ></div>
												<div class="col-sm-9">
											     <button type="submit" class="btn btn-primary btn-lg">Add Receiver</button>
                                                </div>
                                            </div>
											
										</div>
									</div>
									
								</form>
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

<?php $domain_data = preg_replace('#^https?://#', '', Request::root());  ?>
  @if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' || $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in:8021' || $domain_data == 'dmt.ekiosk.in:8021' || $domain_data == 'dmt.aonehub.com:8021' || $domain_data == 'dmt.zippays.in:8021' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8021' || $domain_data == 'dmt.akpayments.in:8021' || $domain_data == 'dmt.oneindiapayments.com:8021')
<div class="loader" style="position: fixed;top: 0%;left: 0%;z-index: 5000;height: 100%;width: 100%;text-align: center;background: rgba(255,255,255,0.8);border: 1px solid #000;" >
	<div><br/><br/><br/><br/><br/><h3>Transaction Processing......</h3><br/><h4>PLEASE DO NOT REFRESH THE PAGE.</h4></div><br />
</div>
  @else
<div class="loader" style="position: fixed;top: 0%;left: 0%;z-index: 5000;height: 100%;width: 100%;text-align: center;background: rgba(255,255,255,0.8);border: 1px solid #000;" >
<img src="/images/cinqueterre.png" >
	<div><br/><br/><br/><br/><br/><h3>Wait For Processing......</h3><br/><h4>PLEASE DO NOT REFRESH THE PAGE.</h4></div><br />
</div>
  @endif
@stop
@section('javascript')


<script>
	angular.module('DIPApp')
		.controller('AddReceiverCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope;
			jQuery(".loader").hide(0);
			var id={{$remitter_id}}
			var phone_no={{$phone_no}}

			$scope.receiver = {}
			$scope.ifscVerified = true
			$scope.registerReceiver=registerReceiver;
			$scope.ifscLookUp = ifscLookUp
			$scope.resetIfsc = resetIfsc

			$scope.banks ={{json_encode($banks)}}

			$scope.masterIfsc = masterIfsc

			function masterIfsc (bankId) {
				$http.get('/api/v1/bank/bankId?bankId='+bankId)
				.then(function (data) {
		
					$scope.bank_ifsc =data.data.master_ifsc_code
					$scope.bank_branch = data.data.bank_branch
					$scope.bank_branch_id = data.data.dp_bank_id
				}, fail)
			}

			// function ifscLookUp (ifsc) {
			// 	$http.get('/api/v1/bank/actions/ifsc?ifsc='+ifsc)
			// 		.then(function (data) {
			// 			$scope.bank_branch_id = data.data.id
			// 			$scope.bankName = data.data.dmt_bank.name
			// 			$scope.bank_branch = data.data.branch
			// 			$scope.bank_ifsc = data.data.ifsc
			// 			$scope.ifscVerified = true
			// 		}, fail)
			// }

				function ifscLookUp (ifsc,bankId) {
					$http.get('/api/v1/bank/actions?ifsc='+ifsc+'&bankId='+bankId)
						.then(function (data) {
							console.log(data)
							if(data.data.status== 1)
							{
								$scope.bank_branch = data.data.bank_branch
							$scope.bank_branch_id = data.data.bank_branch

							}else
							{
								sweetAlert('Error', data.data.description, 'error')
							}
							
				}, fail)
			}

			function resetIfsc () {
				$scope.ifscVerified = false
				$scope.bankName = ""
				$scope.bank_branch = ""
			}
			
			function registerReceiver(receiver){
				$scope.ifscVerified = true
				if ($scope.AddReceiverFrm.$invalid || ! $scope.ifscVerified) return
					 jQuery(".loader").show(0);

                 var filter1={
                 	     id:id,
						name:$scope.receiver.name,
						account_number:$scope.receiver.account_number,
						bank_ifsc:$scope.bank_ifsc,
						bank_name:$scope.receiver.bankName,
						bank_branch:$scope.bank_branch,
						bank_branch_id:$scope.bank_branch_id,

                     }
                    console.log(filter1)
                    if($scope.receiver.account_number!='0000000000'){
                     	$http.post('/beneficiary/webadd', filter1).then(function (data) 
					{ 
						jQuery(".loader").hide(0);
						console.log(data.data.status);
						if(data.data.status == 1)
						{
							var id=data.data.id;	
							sweetAlert('Success', 'Added Successfully', 'success');
							window.location.href = "/remitter/"+id+"/"+phone_no+"/beneficiary/beneficiary_otp"
						}
						else
						{
							sweetAlert('Error', 'Something went wrong', 'error')
						}
					}, fail)
	                              
                	}else
                	{
                     	jQuery(".loader").hide(0);
                     	sweetAlert('Error', 'Please enter valid account number', 'error')
                    }
				                                     
			}

			function customRequiredCheck (model, key) {
				if (! key)
				return (! model || model.length == 0) ? false : true
				return (! _.has(model, key) || model[key] == "") ? false : true
			}

			function fail (err) {
				 jQuery(".loader").hide(0);
				 //console.log(err)
				if (err)
					return sweetAlert('Error', err.data.message, 'error')
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop