<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="ProfileCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">User profile</div>
				<div class="tab-container ">
					<!--  -->
					<div class="tab-content ">
						<div id="profile">
							<div class="">
								<div class="panel-body">
                                    <div class="panel-heading text-center"><strong style="font-size: 15px;">Basic Details</strong></div>
									<form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">USER ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="userid" value="@{{user['user_id']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                      <!--   <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" style="text-transform: uppercase;">@{{user.vendorDetails.type['parent']}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="distributor" value="@{{user['distributorname']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">NAME</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="@{{user['name']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">EMAIL</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="email" value="@{{user['email']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">MOBILE NO</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="mobile_no" value="@{{user['mobileno']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">PAN NO</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="pan_no" value="@{{user['PANNO']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">CITY</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="city" value="@{{user['city']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">STATE</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="state" value="@{{user['state']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">ZONE</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="zone" value="@{{user['zone']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!--  -->
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script>
	angular.module('DIPApp')
	.controller('ProfileCtrl', ['$scope', '$http', function ($scope, $http) {
		window.s = $scope;
		$scope.user ={{$data}};
        
		

		function fail (err) {
			sweetAlert('Error', 'Something went wrong', 'error')
		}
	}])
</script>
@stop