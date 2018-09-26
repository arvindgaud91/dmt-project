<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AddReceiverCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">Receiver Account Validation </div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content">
						<div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
							<div class="panel-body">
								<!-- Section Title -->
								<div class="panel-heading">Receiver Details</div>
								<!-- /Section Title -->
								<!-- Form -->
								<form name="AddReceiverFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="registerReceiver(receiver)" novalidate>
									<div class="col-md-6">
										<div class="row">
											<div class="form-group">
												<label class="col-sm-3 control-label" >Beneficiary Name</label>
												<div class="col-sm-9">
													<input type="text" ng-model="receiver.name" name="name" class="form-control err"  ng-init="receiver.name='<?php echo $ben_name?>'"   placeholder="Enter Beneficiary Name" required ischar>
													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.bname.$invalid" class="err-mark">Please enter the beneficiary name.</p>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-sm-3 control-label" >Account Number</label>
												<div class="col-sm-9">
													<input type="text" ng-model="receiver.account_number" name="accountNo" class="form-control err" ng-init="receiver.account_number='<?php echo $ben_accountno?>'"   placeholder="Enter Account Number" required isnumber>
													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.accountNo.$invalid" class="err-mark">Please enter account number.</p>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 control-label" >IFSC</label>
												<div class="col-sm-6">
													<input type="text"  ng-model="bank_ifsc"  name="ifsc" class="form-control err" placeholder="Enter IFSC" ng-init="bank_ifsc='<?php echo $ben_ifsc?>'" required>

													<!-- <p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.ifsc.$invalid" class="err-mark">Please enter the ifsc.</p>
													<p ng-show="AddReceiverFrm.$submitted && AddReceiverFrm.ifsc.$valid && ! ifscVerified" class="err-mark">Please verify the entered ifsc.</p> -->
												</div>
												<div class="col-sm-3">
													<button type="button" style="margin-top: 7px;" class="btn btn-primary btn-md" ng-click="ifscLookUp(bank_ifsc)" ng-hide="ifscVerified">Search</button>
													<button type="button" style="margin-top: 7px;" class="btn btn-primary btn-md" ng-click="resetIfsc(bank_ifsc)" ng-show="ifscVerified">Change</button>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 control-label" >Bank Name</label>
												<div class="col-sm-9">
												<input type="bank_id"    name="bank_branch_id" ng-model="bank_branch_id" class="hide" disabled >
													<input type="text" ng-model="bank_name" name="bankName" class="form-control err" >
												</div>
											</div>




											<div class="form-group">
												<label class="col-sm-3 control-label" >Branch</label>
												<div class="col-sm-9">
	         <input type="text"    ng-model="bank_branch" name="branchName" class="form-control err" >
												</div>
											</div>

										
											
											<button type="submit" class="btn btn-primary btn-lg" ng-disabled="transactionsprocess">Account Validation</button>
											
										</div>
									</div>
									
								</form>
								<!-- /Form -->
										 <form  method="post"  id="myForm" class="myForm" name="f11" style="width: 460px;" action="/api/v1/actions/search/remitter">
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone_no" maxlength="10" id="phoneNoo" value="<?php echo $phone_no ?>" pattern="[0-9]{10}"  pattern="\d*" title="10 Digit mobile number." placeholder="Search Remitter" style="display:  inline-block; width: 180px; float:  left;"  maxlength="10" required>

                        
                        <input style="display:  inline-block; float: left; margin-top: 10px;" type="submit" class="btn btn-success btn-sm" value="Search">
                       
                    </div>
                </form>
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
  @if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' || $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in:8021' || $domain_data == 'dmt.ekiosk.in:8021' || $domain_data == 'dmt.aonehub.com:8021' || $domain_data == 'dmt.zippays.in:8021' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8021' || $domain_data == 'dmt.akpayments.in:8021' || $domain_data == 'dmt.oneindiapayments.com:8021')
<div class="loader" style="position: fixed;top: 0%;left: 0%;z-index: 5000;height: 100%;width: 100%;text-align: center;background: rgba(255,255,255,0.8);border: 1px solid #000;" >
	<div><br/><br/><br/><br/><br/><h3>Transaction Processing......</h3><br/><h4>PLEASE DO NOT REFRESH THE PAGE.</h4></div><br />
</div>
  @else
<div class="loader" style="position: fixed;top: 0%;left: 0%;z-index: 5000;height: 100%;width: 100%;text-align: center;background: rgba(255,255,255,0.8);border: 1px solid #000;" >
<img src="/images/cinqueterre.png" >
	<div><br/><br/><br/><br/><br/><h3>WE ARE TRYING TO VALIDATE YOUR ACCOUNT.......</h3><br/><h4>PLEASE DO NOT REFRESH THE PAGE.</h4></div><br />
</div>
 @endif
@stop
@section('javascript')
<script type="text/javascript">
			document.f1.submit();

		</script>
<script type="text/javascript">
	$("#myForm").hide();
</script>

<script>
	angular.module('DIPApp')
		.controller('AddReceiverCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope
			jQuery(".loader").hide(0);
			
			$scope.ben_id = {{json_encode($ben_id)}}
			$scope.receiver = {}
			$scope.ifscVerified = true
			$scope.registerReceiver=registerReceiver;
			$scope.ifscLookUp = ifscLookUp
			$scope.resetIfsc = resetIfsc

			function ifscLookUp (ifsc) {
				$http.get('/api/v1/bank/actions/ifsc?ifsc='+ifsc)
					.then(function (data) {
						console.log(data)
						$scope.bank_branch_id = data.data.id
						$scope.bank_name = data.data.bank_address
						$scope.bank_branch = data.data.bank_branch
						$scope.bank_ifsc = ifsc
						$scope.ifscVerified = true
					}, fail)
			}

			function resetIfsc () {
				$scope.ifscVerified = false
				$scope.bank_name = ""
				$scope.bank_branch = ""
			}
			
			function registerReceiver(receiver){
				if ($scope.AddReceiverFrm.$invalid || ! $scope.ifscVerified) return
					$scope.transactionsprocess=true;
             jQuery(".loader").show(0);


                 var filter1={
                       remitter_id:$scope.ben_id,
						name:$scope.receiver.name,
						account_number:$scope.receiver.account_number,
						bank_ifsc:$scope.bank_ifsc,
						bank_name:$scope.bank_name,
						bank_branch:$scope.bank_branch,
						bank_branch_id:$scope.bank_branch_id,

                     }

				$http.post('/validationbeneficiary', filter1).then(data => {
					console.log(data.data.status);
					jQuery(".loader").hide(0);
					if(data.data.status==1)
					{
							sweetAlert('Success', 'Added Successfully', 'success')
				
					document.f11.submit();

			       	}  	
				}, fail)
			}

			function customRequiredCheck (model, key) {
				if (! key)
				return (! model || model.length == 0) ? false : true
				return (! _.has(model, key) || model[key] == "") ? false : true
			}

			function fail (err) {
				//console.log(err);
				jQuery(".loader").hide(0);
				$scope.transactionsprocess=false;
				if (err)
					return sweetAlert('Error', err.data.message, 'error')
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop