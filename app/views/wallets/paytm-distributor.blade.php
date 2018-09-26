<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="WalletRequestCtrl" class="head-weight">
    <div class="row">

       @if (Session::has('error'))
                               <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif

      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Wallet Credit  Request Form - PAYTM</div>
          <div class="row">
            <div class="col-md-8"> 
              <div class="panel1 panel-default">
                <div class="panel-body">
                  <form name="AddrequestFrm" method="post" action="/api/v1/transaction/paytm" >
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Amount</small></label>

                          <input type="text"  class="form-control"  name="TXN_AMOUNT" placeholder="Amount" min="1" max="200000" required>

                        </div>
                      </div>
                     </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" ng-disabled="isDisabled" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT REQUEST</small></button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End Row -->
        </div>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('WalletRequestCtrl', ['$scope', '$http', function ($scope, $http) {

      window.s = $scope;
      
     
      
     
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
