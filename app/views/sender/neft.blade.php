<?php use Acme\Auth\Auth;
$user=Auth::user();

 ?>
@extends('layouts.master')
@section('content')
<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		@if(\Cookie::get('user_type') == 1)
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
													<td><?php echo  $beneficiaryname;?></td>
													<td><?php echo $ifscode;?></td>
													<td><?php echo $accountnumber?></td>
												</tr>
											</tbody>
										</table>
										<br>
										@if($user->id == 8043)
                                              Unauthorized Access 
										@else
										<form name="AddrequestFrm" method="post" style="border-radius: 0px;" class="group-border-dashed" ng-submit="confirmNeft(request)" novalidate>
										<div class="form-group col-md-4">
											<div class="row">
												<label class="control-label" >Amount</label>
												<input type="text" ng-model="request.amount" class="form-control err" name="amount" placeholder="Enter Amount" required isfloat>
												<p ng-show="AddrequestFrm.$submitted && (AddrequestFrm.amount.$invalid || invalidAmount(request.amount))" class="err-mark">Please enter an amount between 100 and 25000.</p>
												<div class="clearfix"></div> <br> 
												
												<button type="submit" class="btn btn-primary btn-lg"ng-disabled="transactionsprocess">Confirm</button>
													
											</div>
										</div>
									</form>

										@endif
										
									<!-- /Form -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Page Content -->
			</div>
		</div>

		@else

<div>
Unauthorized accesss	

</div>

@endif
	</div>
</div>
<?php $domain_data = preg_replace('#^https?://#', '', Request::root());  ?>
  @if($domain_data == 'dmt.mysuravi.com:8021' || $domain_data == 'dmt.lrprch.in:8021' || $domain_data == 'dmt.manjilgroup.com:8021' || $domain_data == 'dmt.indiapaysolution.com:8021' || $domain_data == 'dmt.samriddhifoundation.net.in:8021' ||  $domain_data == 'dmtservices.primagemarketing.com:8021' || $domain_data == 'dmt.amenitiesservices.in:8021' || $domain_data == 'dmt.ekiosk.in:8021' || $domain_data == 'dmt.aonehub.com:8021' || $domain_data == 'dmt.zippays.in:8021' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8021' || $domain_data == 'dmt.akpayments.in:8021' || $domain_data == 'dmt.oneindiapayments:8021')
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
			$scope.consumed_limit ={{$consumed_limit}}

			
		 $scope.updatedBalance = getUpdatedBalance()


			$scope.request = {
				'remitter_id': $scope.remitter_id,
				'beneficiary_id': $scope.beneficiary
			}

			$scope.confirmNeft=confirmNeft;
			$scope.invalidAmount = invalidAmount;



			function confirmNeft(request)
			{
             
             var consumed_limit=parseFloat($scope.consumed_limit).toFixed(2);

			
				if ($scope.AddrequestFrm.$invalid || invalidAmount(request.amount)) return sweetAlert('Error', 'Transaction Amount must be greater than RS.100 ', 'error')
					
				if((25000-consumed_limit)<$scope.request.amount){
	            	sweetAlert('Error', 'Transaction limit exceed.', 'error')
	            	return false
	            }
				if(parseFloat($scope.request.amount) >= 100)
				{
				
				if (! confirm ("Are you sure?")) return sweetAlert('Error', 'Something went wrong', 'error')
				if ($scope.request.amount > 49999) return sweetAlert('Error', 'Transaction Limit is reached, please contact Operation Team for resolution.', 'error')

					$scope.updatedBalance = getUpdatedBalance()
				
				//console.log('2'+$scope.updatedBalance);

				if($scope.wallectBalance <= $scope.request.amount) return sweetAlert('Error', 'Amount limit exceeded', 'error')


				    jQuery(".loader").show(0);
			        $scope.transactionsprocess=true;
			     	
			     	$http.post('/api/v1/transactions/neft', $scope.request).then(data => {
			     		jQuery(".loader").hide(0);
			     		if(data.data.status==1)
			     		{
			     			sweetAlert('Success', 'Transaction requested successfully', 'success')
					window.location.href = "/transactions/neft/beneficiary/transactions/neft/beneficiary/"+data.data.transaction_group_id+'/receipts'
				}else{

                     sweetAlert('Error', data.data.message, 'error')
				     }


					
				     }, tranfail)
			     	$scope.transactionsprocess=false;
			     
 
			  
                    
				}else
					{
						jQuery(".loader").hide(0);
							$scope.transactionsprocess=false;
							 sweetAlert('Error', 'Transaction Amount must be greater than RS.100 ', 'error')
						
						
					} 
					
				
			}


  function getUpdatedBalance () {
                    $http.post('/api/v1/getupdatedbalance', {'user_id': {{Cookie::get('userid')}}})
                        .then(function (data) {
                            
                           // console.log(data.data.wallet_balance)
                            $scope.wallectBalance=data.data.wallet_balance
                            
                        }, failed)
                }

			function invalidAmount (amt) {
				return amt >= 100 && amt <= 25000 ? false : true
			}
			function fail (err) {
				 $scope.transactionsprocess=false;
				jQuery(".loader").hide(0);
				sweetAlert('Error', 'Something went wrong', 'error')
			}
			function failed (err) {
					sweetAlert('Error', err.data.message, 'error')
				}

			function tranfail (err) {
				 $scope.transactionsprocess=false;
				jQuery(".loader").hide(0);
				sweetAlert('Error', err.data.message, 'error')
			}
	}])
</script>
@stop