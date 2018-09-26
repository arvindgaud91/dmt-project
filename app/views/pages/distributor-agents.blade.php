<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="DistributorAgentsCtrl" class="head-weight">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">My Agents (through distributor: @{{distributor.name}})<span class="panel-subtitle">List</span></div>
      <div class="panel-body">
        <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>NAME</th>
                    <th>PHONE NUMBER</th>
                    <th>EMAIL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="agent in agents">
                    <td>@{{agent.name}}</td>
                    <td>@{{agent.phone_no}}</td>
                    <td>@{{agent.email}}</td>
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
    var agents = {{ $agents }}
    var distributor = {{ $distributor }}
  </script>
</div>
@stop
@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('DistributorAgentsCtrl', ['$scope', '$http', function ($scope, $http) {
      window.s = $scope
      $scope.agents = agents
      $scope.distributor = distributor
  
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop