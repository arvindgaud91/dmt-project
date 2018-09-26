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
                            <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                                <div class="panel-body">
                                     <div class="panel-heading text-center">Basic Details</div>
                                    <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" >USER ID</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="userid" value="1" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group"  ng-hide="profileType=='superdistributor'">
                                            <label class="col-sm-3 control-label">Profile Type</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="distributor" value="Type" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">NAME</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="name" value="name" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">EMAIL</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="email" value="email" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">MOBILE NO</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mobile_no" value="mobile no" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">PAN NO</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mobile_no" value="Pan no" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CITY</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="city" value="CITY" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">STATE</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="state" value="STATE" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">ZONE</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="zone" value="ZONE" readonly="readonly" class="form-control">
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
    function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
    }
}])
</script>
@stop