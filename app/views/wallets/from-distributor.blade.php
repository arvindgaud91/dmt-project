<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="WalletRequestCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Wallet Credit Request Form</div>
          <!--<div class="panel-body">
                <div class="row">
            <div class="col-md-12">
              <div class="panel">-->
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="walletRequestObjFrm" novalidate ng-submit="walletRequest(walletRequestObj)">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Distributor</small></label>
                          <input type="text" ng-model="parentname" class="form-control" disabled name="branch" placeholder="Distributor" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                          <input type="text" ng-model="walletRequestObj.amount" class="form-control" name="amount" placeholder="Enter Amount" required isfloat>
                          <p ng-show="walletRequestObjFrm.$submitted && (walletRequestObjFrm.amount.$invalid || invalidAmount(walletRequestObj.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Remarks</small></label>
                          <input type="text" class="form-control" ng-model="walletRequestObj.remarks" name="remarks" placeholder="Enter Remarks">
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
              <!--</div>
            </div>
          </div>
           End Row 
            </div>-->
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
      $scope.parentname = {{json_encode($parent)}}
      $scope.user = user;
      $scope.walletRequest= walletRequest;
      $scope.invalidAmount = invalidAmount
       
      // $scope.walletRequestObj={
      // //'parent_id': $scope.user.vendorDetails['parent_id']
      // }
      function walletRequest(walletRequestObj){
        if ($scope.walletRequestObjFrm.$invalid || invalidAmount(walletRequestObj.amount)) return
        $scope.isDisabled = true;
        $http.post('/api/v1/wallets/balance-requests/from-distributors', walletRequestObj).then(data => {
          sweetAlert('Success', 'Wallet request has been sent', 'success')
          setTimeout(function () {
                        location.reload();
             }, 2000)
        }, fail)
      }
      function invalidAmount (amount) {
        console.log(amount);
        return amount >= 100 && amount <= 2500000 ? false : true
      }
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
