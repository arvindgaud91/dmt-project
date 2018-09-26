<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		@if(\Cookie::get('user_type') == 1)
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">Sender</div>
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
												<th>Remitter ID</th>
												<th>Sender Name</th>
											<th>Mobile Number</th>
												<th>City</th>
												<th>Pincode</th>
												<th>KYC status</th>
												<th>Limit</th>
												<th>Consumed limit</th>
												<th>Action</th>
												
												
											</tr>
										</thead>
										<tbody class="no-border-x">
										
											<tr>
												<td>@{{data.remitterdetail.remitterid}}</td>
												<td>@{{data.remitterdetail.remittername}}</td>
												<td>{{$phone_no}}</td>
												<td>@{{data.remitterdetail.lcity}}</td>
												<td>@{{data.remitterdetail.lpincode}}</td>
												<td ng-if="data.remitterdetail.kycstatus=='7'">
													Approved
												</td>
												<td ng-if="data.remitterdetail.kycstatus!='7'">
													Not Approved
												</td>
												<td>@{{data.remitterdetail.remaininglimit}}</td>
												<td>@{{data.remitterdetail.consumedlimit}}</td>
												<td><a class="btn btn-primary btn-sm" ng-href="/remitter/@{{data.remitterdetail.remitterid}}-{{$phone_no}}/beneficiary/add"><i class="mdi mdi-plus"></i> ADD NEW RECEIVER</a></td>
												
												<!-- <td><a class="btn btn-primary btn-sm" ng-href="/kyc-form/@{{remitter.rbl_remitter_code}}">Apply Kyc</td> -->
											</tr>
											
										</tbody>
									</table>

								
								</div>
								

								<!-- Section Title  -->
								<div class="panel-heading" >Receiver Details</div>
								<!-- /Section Title -->
								<div class="panel-body table-responsive">
									<table class="table table-striped table-borderless" >
										<thead>
											<tr>
												<th>Beneficiary ID</th>
												<th>Receiver Name</th>
												
												<th>Account</th>
												<th>IFSC</th>
												<th>Registered</th>
												<th>Validation</th>
												<th>IMPS</th>
												
												<th>NEFT</th>
												<!-- <th>PAYTM</th> -->
												<th>Action</th>
											</tr>
										</thead>
										<tbody class="no-border-x" ng-if="data.beneficiarydetail.beneficiary.length>0">
											
										@if(!isset($data->beneficiarydetail->beneficiary->beneficiaryemailid))
										@foreach($data->beneficiarydetail->beneficiary as $detail)

											<tr>
												<!-- <td>@{{ben.beneficiaryid}}</td>
												<td>@{{ben.beneficiaryname}}</td>
												<td>@{{ben.accountnumber}}</td>
												<td>@{{ben.ifscode}}</td>
												
												<td ng-if="ben.impsstatus =='0'"><a href="/remitter/@{{data.remitterdetail.remitterid}}-@{{ben.beneficiaryid}}/beneficiary/beneficiary_otpresend"><button class="btn btn-success btn-xs" >Registered</button></a>
												</td>


												<td ng-if="ben.impsstatus =='1'">Registered
												</td>

												<td><a href="/remitter/@{{data.remitterdetail.remitterid}}-@{{ben.beneficiaryname}}-@{{ben.accountnumber}}-@{{ben.ifscode}}/beneficiary/validation"><button type="button" class="btn btn-success btn-xs"> Validation</button></a></td>
                                                
                                              

												 <td ng-if="ben.impsstatus =='1'"
												><a href="/transactions/imps/beneficiary/@{{data.remitterdetail.remitterid}}-@{{ben.beneficiaryid}}-@{{ben.beneficiaryname}}-@{{ben.accountnumber}}-@{{ben.ifscode}}-@{{data.remitterdetail.consumedlimit}}"><button type="button" class="btn btn-success btn-xs"> IMPS</button></a></td>
 
												 <td ng-if="ben.impsstatus =='0'"
												><a href="#"><button type="button" class="btn btn-success btn-xs" disabled>IMPS</button></a></td> 

												
												<td ng-if="ben.impsstatus =='1'"><a href="/transactions/neft/beneficiary/@{{data.remitterdetail.remitterid}}-@{{ben.beneficiaryid}}-@{{ben.beneficiaryname}}-@{{ben.accountnumber}}-@{{ben.ifscode}}-@{{data.remitterdetail.consumedlimit}}"><button type="button" class="btn btn-success btn-xs"> NEFT</button></a></td>

												<td ng-if="ben.impsstatus =='0'"
												><a href="#"><button type="button" class="btn btn-success btn-xs" disabled>NEFT</button></a></td>
												
												<!-- <td>DOWN TIME TILL 10AM</td>
												<td>DOWN TIME TILL 10AM</td> -->

												<!-- <td ng-if="ben.validated=='1'"><a ng-href="/transactions/paytm/beneficiary/@{{ben.id}}" class="btn btn-success  btn-sm"  >PAYTM</a></td>
												<td ng-if="ben.validated=='0'"><a ng-href="/transactions/paytm/beneficiary/@{{ben.id}}" class="btn btn-success  btn-sm" ng-disabled="ben.validated=='0'"  >PAYTM</a></td> -->

												<!-- <td><button type="button" class="btn btn-danger btn-xs" ng-click="deleteBeneficiary(ben.beneficiaryid, data.remitterdetail.remitterid)"> DELETE</button></td> -->
												<td>{{$detail->beneficiaryid}}</td>
												<td>{{$detail->beneficiaryname}}</td>
												
												<td>{{$detail->accountnumber}}</td>
												<td>{{$detail->ifscode}}</td>
												
												@if($detail->impsstatus =='0')
												<td><a href="/remitter/{{$data->remitterdetail->remitterid}}-{{$detail->beneficiaryid}}/{{$phone_no}}/beneficiary/beneficiary_otp"><button class="btn btn-success btn-xs" >Registered</button></a>
												</td>
												@else

												<td >Registered
												</td>
												@endif

												
												<?php $beneficiaryname = preg_replace("/[^a-zA-Z]/", "", $detail->beneficiaryname); ?>
												@if(isset($detail->bene_status) && $detail->bene_status == 0)
												<td><a href="/remitter/{{$data->remitterdetail->remitterid}}-{{$beneficiaryname}}-{{$detail->accountnumber}}-{{$detail->ifscode}}-{{$phone_no}}/beneficiary/validation"><button type="button" class="btn btn-warning btn-xs"> Validation</button></a></td>
												@else
												
												<td><a href="/remitter/{{$data->remitterdetail->remitterid}}-{{$beneficiaryname}}-{{$detail->accountnumber}}-{{$detail->ifscode}}-{{$phone_no}}/beneficiary/validation"><button type="button" class="btn btn-success btn-xs"> DONE</button></a></td>
												@endif
												
                                              
												@if($detail->impsstatus =='1')
												<?php $beneficiaryname ?>
												 <td>
												 	{{ Form::open(array('url' => '/transactions/imps/beneficiary','method'=>'POST','files'=>'true')) }}	
													<input type='hidden' name="remitterid" value="{{$data->remitterdetail->remitterid}}" />
													<input type='hidden' name="beneficiaryid" value="{{$detail->beneficiaryid}}"  />
													<input type='hidden' name="beneficiaryname" value="{{$beneficiaryname}}"  />
													<input type='hidden' name="accountnumber" value="{{$detail->accountnumber}}"  />
													<input type='hidden' name="ifscode" value="{{$detail->ifscode}}" />
													<input type='hidden' name="consumedlimit" value="{{$data->remitterdetail->consumedlimit}}"  />
													<button type="submit" class="btn btn-success btn-xs"> IMPS</button>
												{{Form::close()}}</td>
												
 												@else
												 <td
												><a href="#"><button type="button" class="btn btn-success btn-xs" disabled>IMPS</button></a></td> 
												@endif

												
												@if($detail->impsstatus =='1')
												
												<td>
													{{ Form::open(array('url' => '/transactions/neft/beneficiary','method'=>'POST','files'=>'true')) }}	
													<input type='hidden' name="remitterid" value="{{$data->remitterdetail->remitterid}}" />
													<input type='hidden' name="beneficiaryid" value="{{$detail->beneficiaryid}}"  />
													<input type='hidden' name="beneficiaryname" value="{{$beneficiaryname}}"  />
													<input type='hidden' name="accountnumber" value="{{$detail->accountnumber}}"  />
													<input type='hidden' name="ifscode" value="{{$detail->ifscode}}" />
													<input type='hidden' name="consumedlimit" value="{{$data->remitterdetail->consumedlimit}}"  />
													<button type="submit" class="btn btn-success btn-xs"> NEFT</button>
												{{Form::close()}}
											</td>

												
												@else
								
												<td><a href="#"><button type="button" class="btn btn-success btn-xs" disabled>NEFT</button></a></td>

												
												@endif
												<!--  <td>NPCI IMPS Down</td>

												 <td>NPCI IMPS Down</td>
												
												<td>NPCI NEFT Down</td>
 -->
												<!-- <td ng-if="ben.validated=='1'"><a ng-href="/transactions/paytm/beneficiary/@{{ben.id}}" class="btn btn-success  btn-sm"  >PAYTM</a></td>
												<td ng-if="ben.validated=='0'"><a ng-href="/transactions/paytm/beneficiary/@{{ben.id}}" class="btn btn-success  btn-sm" ng-disabled="ben.validated=='0'"  >PAYTM</a></td> -->

												<td><button type="button" class="btn btn-danger btn-xs" ng-click="deleteBeneficiary({{$detail->beneficiaryid}}, {{$data->remitterdetail->remitterid}})"> DELETE</button></td>

												

											</tr>
											@endforeach
											@endif
										</tbody>
										@if(isset($data->beneficiarydetail->beneficiary->beneficiaryname))
										<?php $beneficiaryname1 = preg_replace("/[^a-zA-Z]/", "", $data->beneficiarydetail->beneficiary->beneficiaryname); ?>
										<tbody class="no-border-x">
									
											<tr>
												<td>{{$data->beneficiarydetail->beneficiary->beneficiaryid}}</td>
												<td>{{$data->beneficiarydetail->beneficiary->beneficiaryname}}</td>
												<td>{{$data->beneficiarydetail->beneficiary->accountnumber}}</td>
												<td>{{$data->beneficiarydetail->beneficiary->ifscode}}</td>
												
												@if($data->beneficiarydetail->beneficiary->impsstatus=='0')
												<td><a href="/remitter/{{$data->remitterdetail->remitterid}}-{{$data->beneficiarydetail->beneficiary->beneficiaryid}}/{{$phone_no}}/beneficiary/beneficiary_otp"><button class="btn btn-success btn-xs" >Registered</button></a>
												</td>
												@else

												<td>Registered
												</td>
												@endif

												@if(isset($data->beneficiarydetail->beneficiary->bene_status)=='0')

												<td><a href="/remitter/{{$data->remitterdetail->remitterid}}-{{$beneficiaryname1}}-{{$data->beneficiarydetail->beneficiary->accountnumber}}-{{$data->beneficiarydetail->beneficiary->ifscode}}/{{$phone_no}}/beneficiary/validation"><button type="button" class="btn btn-warning btn-xs"> Validation</button></a></td>
												@else
												
												<td><a href="/remitter/{{$data->remitterdetail->remitterid}}-{{$beneficiaryname1}}-{{$data->beneficiarydetail->beneficiary->accountnumber}}-{{$data->beneficiarydetail->beneficiary->ifscode}}/{{$phone_no}}/beneficiary/validation"><button type="button" class="btn btn-success btn-xs"> DONE</button></a></td>
												@endif

												
                                                
                                              
												@if($data->beneficiarydetail->beneficiary->impsstatus=='1')
												<td>
													{{ Form::open(array('url' => '/transactions/imps/beneficiary','method'=>'POST','files'=>'true')) }}	
													<input type='hidden' name="remitterid" value="{{$data->remitterdetail->remitterid}}" />
													<input type='hidden' name="beneficiaryid" value="{{$data->beneficiarydetail->beneficiary->beneficiaryid}}"  />
													<input type='hidden' name="beneficiaryname" value="{{$beneficiaryname1}}"  />
													<input type='hidden' name="accountnumber" value="{{$data->beneficiarydetail->beneficiary->accountnumber}}"  />
													<input type='hidden' name="ifscode" value="{{$data->beneficiarydetail->beneficiary->ifscode}}" />
													<input type='hidden' name="consumedlimit" value="{{$data->remitterdetail->consumedlimit}}"  />
													<button type="submit" class="btn btn-success btn-xs"> IMPS</button>
												{{Form::close()}}
											</td>
												
												@else
												<td><a href="#"><button type="button" class="btn btn-success btn-xs" disabled>IMPS</button></a></td>
												@endif
												 
												@if($data->beneficiarydetail->beneficiary->impsstatus=='1')

												<td>
													{{ Form::open(array('url' => '/transactions/neft/beneficiary','method'=>'POST','files'=>'true')) }}	
													<input type='hidden' name="remitterid" value="{{$data->remitterdetail->remitterid}}" />
													<input type='hidden' name="beneficiaryid" value="{{$data->beneficiarydetail->beneficiary->beneficiaryid}}"  />
													<input type='hidden' name="beneficiaryname" value="{{$beneficiaryname1}}"  />
													<input type='hidden' name="accountnumber" value="{{$data->beneficiarydetail->beneficiary->accountnumber}}"  />
													<input type='hidden' name="ifscode" value="{{$data->beneficiarydetail->beneficiary->ifscode}}" />
													<input type='hidden' name="consumedlimit" value="{{$data->remitterdetail->consumedlimit}}"  />
													<button type="submit" class="btn btn-success btn-xs"> NEFT</button>
												{{Form::close()}}
											</td>
									
			
												@else
      
                                        		<td><a href="#"><button type="button" class="btn btn-success btn-xs" disabled>NEFT</button></a></td>

												@endif
												
<!-- <td>NPCI IMPS Down</td>
<td>NPCI IMPS Down</td>
												
												<td>NPCI NEFT Down</td> -->



												<!-- <td ng-if="ben.validated=='1'"><a ng-href="/transactions/paytm/beneficiary/@{{ben.id}}" class="btn btn-success  btn-sm"  >PAYTM</a></td>
												<td ng-if="ben.validated=='0'"><a ng-href="/transactions/paytm/beneficiary/@{{ben.id}}" class="btn btn-success  btn-sm" ng-disabled="ben.validated=='0'"  >PAYTM</a></td> -->

												<td><button type="button" class="btn btn-danger btn-xs" ng-click="deleteBeneficiary(data.beneficiarydetail.beneficiary.beneficiaryid, data.remitterdetail.remitterid)"> DELETE</button></td>

											

											</tr>
											
										</tbody>
										@endif
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
@else

<div>
Unauthorized accesss	

</div>

@endif

	</div>
</div>

@stop
@section('javascript')




<script>
	angular.module('DIPApp')
		.controller('SenderListCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope
			
			$scope.data ={{json_encode($data)}}	
			$scope.phone_no ={{json_encode($phone_no)}}	

			//console.log($scope.data)
			$scope.deleteBeneficiary = deleteBeneficiary
$scope.resendOtp = resendOtp

			function deleteBeneficiary (ben_id,remitterid) 
			{
				

				var filter1={
                 	     ben_id:ben_id,
						remitterid:remitterid,
						
                     }
				$http.post('/deleteBeneficiaryweb', filter1)
					.then(function (data) {		
					console.log(data);		
						sweetAlert('Success', 'OTP Send Successfully ', 'success')
						window.location.href = "/remitter/"+ben_id+'-'+remitterid+"/beneficiary/delete_otp"
						
					})




			}





			function resendOtp (remitterid,ben_id) {
				

					 var filter1={
                 	     ben_id:ben_id,
						remitterid:remitterid,
						
                     }

				$http.post('/api/v1/beneficiary-delete-resendotp/'+ben_id+'-'+remitterid, filter1).then(data => {
					sweetAlert('Success', 'OTP Sent to Remitters Mobile No', 'success')
				}, fail)


			}

			function fail (err) {
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop