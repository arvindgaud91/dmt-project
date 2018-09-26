<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AddSenderCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<!-- Page Title -->
				<div class="panel-heading">Add Sender</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content">
						<div class="panel">
							<div class="panel-body">
								<!-- Section Title -->
								
								<!-- /Section Title -->
								<!-- Form -->
								<form name="AddSenderFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="registerSender(sender)" novalidate>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <div class="panel-heading"><strong style="font-size: 15px;">Sender Details</strong></div>
                                        </div>
                                    </div>
									<div class="col-md-6">
										<div class="row">
											<div class="form-group">
												<label class="col-sm-3 control-label" >Name</label>
												<div class="col-sm-9">
													<input type="text" ng-model="sender.name" name="name" class="form-control err" placeholder="Enter Name" required ischar>
													<p ng-show="AddSenderFrm.$submitted && AddSenderFrm.name.$invalid" class="err-mark">Please enter the Name.</p>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-sm-3 control-label" >Mobile</label>
												<div class="col-sm-9">
													<input type="text" onkeyup="sync()" id="phoneNo" ng-model="sender.phone_no" name="phoneNo" class="form-control err" ng-init="sender.phone_no = '<?php echo $phone_no; ?>'"  maxlength="10" placeholder="Enter Mobile" required isphoneno>
													<p ng-show="AddSenderFrm.$submitted && AddSenderFrm.phoneNo.$invalid" class="err-mark">Please enter a valid mobile.</p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label" >Pincode</label>
												<div class="col-sm-9">
													<input type="text" ng-model="pincode" name="pincode" class="form-control err" placeholder="Enter Pincode"  maxlength="6" minlength="6" required isvalidpincode ng-change="pincodeLookUp(pincode)">
													<p ng-show="AddSenderFrm.$submitted && AddSenderFrm.pincode.$invalid" class="err-mark">Please enter a valid pincode.</p>
													<p ng-show="AddSenderFrm.$submitted && AddSenderFrm.pincode.$valid && ! pincodeVerified" class="err-mark">Please verify the entered pincode.</p>
												</div>
												<!-- <div class="col-sm-3">
													<button type="button" style="margin-top: 7px;" class="btn btn-primary btn-md" ng-click="pincodeLookUp(pincode)">Search</button>
												</div> -->
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label" >Address</label>
												<div class="col-sm-9">
													<input type="text" ng-model="address" name="address" class="form-control err" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label" >City</label>
												<div class="col-sm-9">
													<input type="text" ng-model="city" name="city" class="form-control err" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label" >State</label>
												<div class="col-sm-9">
													<input type="text" ng-model="state" name="state" class="form-control err" readonly>
												</div>
											</div>
											
                                            <div class="form-group">
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-9">
											     <!--<button type="submit" class="btn btn-primary btn-lg">Add Sender</button>-->
                                                <button type="submit" class="btn btn-primary">
                                                   Add Sender
                                               </button>
                                                </div>
                                            </div>
										</div>
									</div>
									
								</form>
								 <form  method="post"  id="myForm" class="myForm" name="f11" style="width: 460px;" action="/api/v1/actions/search/remitter">
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone_no" maxlength="10" id="phoneNoo" value="<?php echo $phone_no; ?>"  pattern="[0-9]{10}"  pattern="\d*" title="10 Digit mobile number." placeholder="Search Remitter" style="display:  inline-block; width: 180px; float:  left;"  maxlength="10" required>

                        
                        <input style="display:  inline-block; float: left; margin-top: 10px;" type="submit" class="btn btn-success btn-sm" value="Search">
                       
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
  @if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' || $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in')
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
function sync()
{
  var n1 = document.getElementById('phoneNo');
  var n2 = document.getElementById('phoneNoo');
  n2.value = n1.value;
}
</script>
<script type="text/javascript">
			document.f1.submit();

		</script>
<script type="text/javascript">
	$("#myForm").hide();
</script>

<script>
	angular.module('DIPApp')
		.controller('AddSenderCtrl',['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload)  {
			window.s = $scope;
			jQuery(".loader").hide(0);
			$scope.registerSender=registerSender;
			$scope.pincodeLookUp = pincodeLookUp
			$scope.resetPincode = resetPincode
			$scope.pincodeVerified = true

			function pincodeLookUp (pincode) {
				$http.post('/pincode' ,pincode)
					.then(function (data) {
						console.log(data);
						var abc = data.data
						$scope.abc = abc
						if(abc){
							console.log(abc)
						}
						console.log(data.data.data);
						$scope.address = data.data.data.postal_region
						$scope.city = data.data.data.city
						$scope.state = data.data.data.state
						$scope.pincodeVerified = true
					}, fails)
			}

			function resetPincode () {
				$scope.pincodeVerified = false
				$scope.address = ""
				$scope.city = ""
				$scope.state = ""
			}
			function registerSender(sender){
				if ($scope.AddSenderFrm.$invalid || ! $scope.pincodeVerified) return
					jQuery(".loader").show(0);
					var filter1={
						name:$scope.sender.name,
						phone_no:$scope.sender.phone_no,
						pincode:$scope.pincode,
						address:$scope.address,
						city:$scope.city,
						state:$scope.state			
                    }
				if($scope.sender.phone_no == 0000000000)                    
				{
					jQuery(".loader").hide(0);
					sweetAlert('Error', 'Please enter correct Phone number', 'error');
					return false;
				}
				
				$http.post('/remitter', filter1).then(data => {
					if(data.data.status==1)
					{
							sweetAlert('Success', 'Added Successfully', 'success')
				
					document.f11.submit();

			       	}    

				}, fail)
				
			}
			function fail (err) {
				 jQuery(".loader").hide(0);
				console.log(err.data.message)
				sweetAlert('Error', err.data.message, 'error')
			}

			function fails (err) {
				 jQuery(".loader").hide(0);
				console.log(err.data.message)
				sweetAlert('Error', 'Pincode Invalid', 'error')
			}
	}])
</script>
@stop
