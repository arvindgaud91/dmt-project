<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="TestCtrl" class="head-weight">
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default panel-border-color panel-border-color-primary">
            <div class="panel-heading panel-heading-divider">@{{labelDict[transactionType]}}<span class="panel-subtitle">Form</span></div>
            <div class="panel-body">
               <form ng-submit="submit()" style="border-radius: 0px;" class="form-horizontal group-border-dashed"  name="WithdrawFrm" novalidate>
                  <div class="row">
                     <div class="col-md-9">
                        <div class="form-group">
                           <label class="col-sm-3 control-label" >Aadhaar Number</label>
                           <div class="col-sm-6">
                              <input type="text" ng-model="transaction.aadhar_no" name="aadhar_no" id="adhar_no" maxlength="12"  pattern="[0-9]{12}" placeholder="Enter Adhar No" class="form-control err"  required isaadharno >
                              <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.aadhar_no.$invalid" class="err-mark">Please enter the aadhaar number.</p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-3 control-label">Select Bank</label>
                           <div class="col-sm-6">
                              <div
                                 isteven-multi-select
                                 input-model="banks"
                                 output-model="bank"
                                 button-label="name"
                                 item-label="name"
                                 tick-property="ticked"
                                 selection-mode="single"
                                 id="Bank"
                                 name="bank"
                                 class="multiselect-form-control"
                                 ng-class="{'err-mark': (WithdrawFrm.$submitted && ! customRequiredCheck(bank))}"
                                 >
                              </div>
                              <p ng-show="WithdrawFrm.$submitted && ! customRequiredCheck(bank)" class="err-mark">Please select a bank.</p>
                           </div>
                        </div>
                        <div class="form-group" ng-show>
                           <label class="col-sm-3 control-label">Select Service</label>
                           <div class="col-sm-6">
                              <input type="text" value="Balance Enquiry" readonly="readonly" class="form-control" name="service" id="show_hide" >
                           </div>
                        </div>
                        <div class="slidingDiv" ng-show="transactionType=='withdraw' || transactionType=='deposit'">
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Enter Amount</label>
                              <div class="col-sm-6">
                                 <input type="number" ng-model="transaction.amount" name="amount" min="1" max="10000" class="form-control err" placeholder="Enter Amount" ng-required="transactionType=='withdraw' || transactionType=='deposit'" isnumber>
                                 <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.amount.$invalid" class="err-mark">Please enter the amount.</p>
                              </div>
                           </div>
                        </div>
                        <div class="form-group login-submit">
                           <div class="col-sm-3"></div>
                           <div class="col-sm-6">
                              <button  type="submit" name="submit" class="btn btn-primary form-control">Submit Form</button>
                           </div>
                           <div class="col-sm-3"></div>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group">
                           <div class="row">
                              <div class="col-sm-6" >
                                 <img id="image" name="fingerImg"  ng-src="data:image/png;base64,@{{fingerImg}}" class="fingerImg" ng-class="{'err-mark': (WithdrawFrm.$submitted && !checkFingerPrint())}">
                                 <p ng-show="WithdrawFrm.$submitted && !checkFingerPrint()" class="err-mark">Please enter your fingerprint.</p>
                              </div>
                           </div>
                           <br>
                           <div class="row">
                              <button type="button" class="btn btn-primary col-sm-6" ng-click="capture()">Capture</button>
                           </div>
                        </div>
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
   .controller('TestCtrl', ['$scope', '$http', function ($scope, $http) {
      window.s = $scope;
      $scope.transactionType = {{ json_encode($transactionType) }}
      $scope.transaction={'action': $scope.transactionType};
      $scope.capture = capture;
      $scope.customRequiredCheck = customRequiredCheck
      $scope.submit = submit
      $scope.checkFingerPrint=checkFingerPrint
      $scope.labelDict = {
         'balance-enquiry': 'Balance Enquiry',
         'withdraw': 'Withdraw',
         'deposit': 'Deposit'
      }
      $scope.banks = [
         {id:"1",name: "Ratnakar Bank"},
         {id:"2",name: "Axis Bank"},
         { id:"3",name: "Union Bank Of India"},
         { id:"4",name: "Bank of Maharashtra"},
      ]
      function capture () {
         $http.get('https://localhost:15005/CaptureFingerprint?10$1')
         .then(data => {
            $scope.fingerImg = data.data.Base64BMPIMage;
            $scope.transaction.fingerprint = data.data.Base64ISOTemplate
         });
      }
      function customRequiredCheck (model, key) {
         if (! key)
         return (! model || model.length == 0) ? false : true
         return (! _.has(model, key) || model[key] == "") ? false : true
      }
      function checkFingerPrint () {
         return (! $scope.transaction.fingerprint || $scope.transaction.fingerprint == '') ?  false : true
      }
      function submit () {
         if ($scope.WithdrawFrm.$invalid) {
            alert('Fill all details correctly')
            return
         }
         if (!checkFingerPrint()) return
         console.log('success');
         var transaction = Object.assign($scope.transaction, {'bank': $scope.bank[0].id, 'bank_iin': $scope.bank[0].iin});
         // console.log(transaction)
         $http.post('/api/v1/transactions', transaction);
      }
      function fail (err) {
         console.log(err)
         sweetAlert('Error', 'Something went wrong', 'error')
      }
   }])
   </script>
   @stop
