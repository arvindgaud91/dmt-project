<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="WalletRequestCtrl" class="head-weight">
      <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary hpanel hgreen">
                    <div class="panel-heading panel-heading-divider"><h4 style="margin:0;">Wallet Credit Request</h4><!--<span class="panel-subtitle">Use to credit your wallet</span>--><p><small>Use to credit your wallet</small></p></div>
                    <div class="panel-body">
                      <b style="color: red;">
                      1)We are not accepting any deposits in Axis Bank effective today 09/07/18.
                      <!-- <br><br>
                      2)Kotak Deposit will be down from (14/07/2018) till sunday night (15/07/2018).Please do not make any deposits in Kotak. -->
                      </b>
                    </div>
          <div class="panel-body">
            <!-- Start row -->
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div role="alert">
                    <p><strong><small>ACCOUNT: DIGITAL INDIA PAYMENTS LIMITED</small></strong></p>
                    <p><small>BANK : ICICI BANK LIMITED</small></p>
                    <p><small>BRANCH : CIBD MUMBAI BRANCH</small></p>
                    <p><small>ACCOUNT NO : 039305008196</small></p>
                    <p><small>IFSC : ICIC0000393</small></p>
                  </div>
                </div>
                <!-- <div class="col-md-6">
                  <div role="alert">
                    <p><strong><small>ACCOUNT: DIGITAL INDIA PAYMENTS LIMITED</small></strong></p>
                    <p><small>BANK : AXIS BANK LIMITED</small></p>
                    <p><small>BRANCH : GOREGAON LINK ROAD</small></p>
                    <p><small>ACCOUNT NO : 916020029292747</small></p>
                    <p><small>IFSC : UTIB0000219</small></p>
                  </div>
                </div> -->
                <div class="col-md-6">
                  <div role="alert">
                    <p><strong><small>ACCOUNT: DIGITAL INDIA PAYMENTS LIMITED</small></strong></p>
                    <p><small>BANK : KOTAK BANK LTD</small></p>
                    <p><small>BRANCH : Powai</small></p>
                    <p><small>ACCOUNT NO : 0112783976</small></p>
                    <p><small>IFSC : KKBK0001399</small></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End row -->
                    </div>
      </div>
    </div>
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
                          <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                          <input type="text" ng-model="walletRequestObj.amount" class="form-control" name="amount" placeholder="Enter Amount" required isfloat>
                          <p ng-show="walletRequestObjFrm.$submitted && (walletRequestObjFrm.amount.$invalid || invalidAmount(walletRequestObj.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label" for="mode"><small>Select Mode of Transfer</small></label>
                          <select ng-options="key as value for (key, value) in modeOfTransferDictS" ng-model="walletRequestObj.transfer_mode" class="form-control" id="mode" name="mode" required>
                            <option value="">SELECT OPTION</option>
                          </select>
                          <p ng-show="walletRequestObjFrm.$submitted && walletRequestObjFrm.mode.$invalid" class="err-mark">Please select a mode of transfer.</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label" for="bank"><small>Select Bank Account</small></label>
                          <select ng-options="key as value for (key, value) in walletBanks" ng-model="walletRequestObj.bank" class="form-control" id="bank" name="bank" required>
                            <option value="">SELECT BANK</option>
                          </select>
                          <p ng-show="walletRequestObjFrm.$submitted && walletRequestObjFrm.bank.$invalid" class="err-mark">Please select a bank.</p>
                        </div>
                      </div>
                      
                    </div>
                   <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Branch</small></label>
                          <input type="text" ng-model="walletRequestObj.branch" class="form-control" name="branch" placeholder="DEPOSIT BRANCH" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Reference Number<font color="red"> * (Required)</font></small></label>
                          <input type="text" class="form-control" ng-model="walletRequestObj.reference_number" name="reference_number" placeholder="REFERENCE NUMBER" required>
                          <p ng-show="walletRequestObjFrm.$submitted && walletRequestObjFrm.reference_number.$invalid" class="err-mark">Please enter the reference number.</p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="button" class="btn btn-success" ng-disabled="isDisabled" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT REQUEST</small></button>
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
  
      $scope.user = user;
      $scope.walletRequest= walletRequest;
      // $scope.walletRequestObj={
      // //'parent_id': $scope.user.vendorDetails['parent_id']
      // }
      function walletRequest(walletRequestObj){
        console.log($scope.walletRequestObjFrm.$invalid);
        if ($scope.walletRequestObjFrm.$invalid) return
        $scope.isDisabled = true;
       $scope.isDisabled = true;
        req = Object.assign(balanceRequest, {user_id: {{Cookie::get('userid')}}})
        $http.post('/api/v1/wallets/balance-requests', req)
        .then(data => {
        location.reload();
        }, fail)
      }
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
