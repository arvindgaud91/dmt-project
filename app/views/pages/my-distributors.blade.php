<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AgentsCtrl" class="head-weight">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">My Distributors<span class="panel-subtitle">List</span></div>
      <div class="panel-body">
        <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>NAME</th>
                   
                    <th>EMAIL</th>
                    <th>MOBILE</th>
                    <th>JOINING DATE</th>
                    <th>BALANCE</th>
                    <th  style="padding-left: 4%">ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="agent in agents">
                    <td>@{{agent.name}}</td>
                    
                    <td>@{{agent.email}}</td>
                    <td>@{{agent.phone_no}}</td>
                    <td>@{{agent.created_at | date:'MMM dd, yyyy'}}</td>
                    <td>@{{agent.balance | currency: 'Rs. '}}</td>
                    <td>
                      <button type="button" ng-click="credit(agent)" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Credit</button>
                      <button type="button" ng-click="debit(agent)" class="btn btn-primary" style="margin-left: 5%;"><i class="fa fa-minus"></i>&nbsp;&nbsp;Debit</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <h4 ng-show="agents.length == 0">No agents added yet</h4>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    var agents = {{ json_encode($agents) }}
   var dmtVendorBalance= {{ json_encode($dmtVendorBalance) }}
//console.log(dmtVendorBalance);
  </script>
</div>
@stop
@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('AgentsCtrl', ['$scope', '$http', function ($scope, $http) {
      window.s = $scope
      $scope.agents = agents.map(formatAgent)
      $scope.credit = credit
      $scope.debit = debit
      $scope.dmtVendorBalance = dmtVendorBalance

      function formatAgent (agent) {
        agent.created_at = new Date(agent.created_at)
         agent.balance = dmtVendorBalance[agent.vendor.user_id]
        return agent
      }

      function credit (agent) {
        if ($scope.activeUserProfile.dmt_vendor.balance == 0)
          return sweetAlert('Error', 'Insufficient Balance.', 'error')
        location.href = `/users/actions/credit-request/vendor/${agent.id}`
      }
      function debit (agent) {
        location.href = `/users/actions/debit-request/vendor/${agent.id}`
      }
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
