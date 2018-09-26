<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="DeviceSelectCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Biometric Device<span class="panel-subtitle">Form</span></div>
          <div class="panel-body">
            <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4 col-sm-offset-3">
                    <select class="form-control" name="device">
                      <option value="">Select Device</option>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <button type="submit" name="submit" class="form-control btn btn-primary btn-sm">Update</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('DeviceSelectCtrl', ['$scope', '$http', function ($scope, $http) {


      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
