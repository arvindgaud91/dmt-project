<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')

<div ng-controller="SenderListCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">

				<!-- Page Title -->
				<div class="panel-heading">KYC List</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				<div class="tab-container ">
					<div class="tab-content ">
						<div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
							<div class="panel-body">
								<!-- Section Title -->
								<div class="panel-heading">KYC Details</div>
								<!-- /Section Title -->
								@if (Session::has('message'))
                               <div class="alert alert-success">{{ Session::get('message') }}</div>
                                @endif

                                @if (Session::has('error'))
                               <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif

								<div class="panel-body">
									<div class="col-md-8">
										<a class="btn btn-primary btn-sm" href="/add-receiver"> Copy </a>
										<a class="btn btn-primary btn-sm" href="/add-receiver"> CSV </a>
										<a class="btn btn-primary btn-sm" href="/add-receiver"> Excel </a>
										<a class="btn btn-primary btn-sm" href="/add-receiver"> Print </a>
										<a class="btn btn-primary btn-sm" href="/add-receiver"> Show 10 rows </a>
									</div>
									<div class="col-md-4 search">
										<div class="col-md-3">
											<label class="control-label" >Search</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" name="name" placeholder="Search">	
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="table-responsive">
										<table class="table table-striped table-borderless">
											<thead>
												<tr>
													<th>Remitter ID</th>
													<th>Mobile</th>
													<th>PAN Card</th>
													<th>Address Proof</th>
													<th>Registration Form</th>
													<th>Action</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody class="no-border-x">
												
												@foreach($data as $datas)
												<tr>
													<td>{{$datas['remitter_id']}}</td>
													<td>{{$datas['mobile']}}</td>
													<td><a href="/upload/kyc/<?=$datas['kyc_pan_card']?>" download="<?=$datas['kyc_pan_card']?>">PAN Card</a></td>
													<td><a href="/upload/kyc/<?=$datas['kyc_add_proof']?>" download="<?=$datas['kyc_add_proof']?>">Address Proof</a></td>
													<td><a href="/upload/kyc/<?=$datas['kyc_req_form']?>" download="<?=$datas['kyc_req_form']?>">Registration Form</a></td>
													<td>@if($datas['isUploaded']==0)

													<a href="/updateuploaded/<?=$datas['id']?>">Documents Uploaded</a>
                                                      @else
                                                       Done 
                                                      @endif
													</td>
													<td>
                                                     <?php

													$time_elapsed = 0;
													$upload_time=$datas['updated_at'];
						                            if($upload_time != '' && !is_null($upload_time)) {
						                            $time =strtotime($upload_time);
						                            //echo $time;
						                                $time_elapsed= (int) round(abs(time() - $time) / 60);
						                            }
						                            ?>
						                            @if($datas['isUploaded']==1)
                                                    @if($time_elapsed > 10)
                                                   <a href="/getRemitterDetails/<?=$datas['remitter_id']?>">Call API</a>
                                                    @else
                                                     Not 
                                                    @endif    
                                                    @else
                                                    NOT 
                                                    @endif
													</td>
													</tr>
													@endforeach
												

											</tbody>
										</table>
									</div>
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

@stop
@section('javascript')


		

	<script src="http://4review.firstquadrant.co.in/firstpay/public/js/ng-file-upload-shim.js"></script>	
	<script src="http://4review.firstquadrant.co.in/firstpay/public/js/ng-file-upload-shim.min.js"></script>	
	<script src="http://4review.firstquadrant.co.in/firstpay/public/js/ng-file-upload.min.js"></script>

<script>
	angular.module('DIPApp')
		.controller('SenderListCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope

			$scope.FieldDisabled=false;
			
			function fail (err) {
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>

</script>
@stop