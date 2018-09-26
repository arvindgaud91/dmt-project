<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="RequestCtrl" class="head-weight">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">Incoming Request</div>
      <div class="panel-body">
        <form  style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>DISTRIBUTOR</th>
                    <th>AMOUNT</th>
                    <th>REMARK</th>
                    <th>ACTION</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Karan</td>
                    <td>1250</td>
                    <td>test</td>
                    <td>
                      <button ng-click="approve()" class="btn btn-xs btn-success">Approve</button>
                      <button ng-click="reject()" class="btn btn-xs btn-danger">Reject</button>
                    </td>
                  </tr>
                </tbody>
              </table>
             <!--  <h4 ng-show="agents.length == 0">No agents added yet</h4> -->
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
</div>
@stop
@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('RequestCtrl', ['$scope', '$http', function ($scope, $http) {
      window.s = $scope
    $scope.approve=approve;
    function approve() {
      if(! confirm('Are you sure?')) return;
      $http.get('test')
      .then(function(data){
        toastr.success('Approved');
      }, fail);
    };
    

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
