<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="HomeCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Dashboard<span class="panel-subtitle">Snapshot</span></div>
          <div class="panel-body">
            <!-- Start row -->
            <div class="row">
              <div class="col-md-4">
                <h4>Work in progress</h4>
              </div>
            </div>
            <!-- End row -->
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {


      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
