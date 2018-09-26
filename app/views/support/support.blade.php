<?php

use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="WalletRequestCtrl" class="head-weight">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider">Support</div>
                <div class="panel-body">
                    <!-- Start row -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- <div id="error">
                              <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
                              </div>
                            </div> -->
                            <!--div class="alert alert-primary" role="alert">
                              <p><strong><small>ACCOUNT: DIGITAL INDIA PAYMENTS LIMITED</small></strong></p>
                              <p><small>BANK : ICICI BANK LIMITED</small></p>
                              <p><small>BRANCH : CIBD MUMBAI BRANCH</small></p>
                              <p><small>ACCOUNT NO : 039305008197</small></p>
                              <p><small>IFSC : ICIC0000393</small></p>
                            </div>
                          </div-->
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <!--<div class="tools"><span class="icon mdi mdi-more-vert"></span></div>-->
                                    <div class="title">Support Request Form</div>
                                </div>
                                <div class="panel-body table-responsive">
                                    <form class="form-signin" name="balanceRequestFrm" ng-submit="submit(balanceRequest)" novalidate>
                                        <div class="row">
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="type"><small>Type</small></label>
                                                    <!--select ng-model="balanceRequest.type" class="form-control" id="type" name="type" required>
                                                        <option value="">SELECT TYPE</option>
                                                        <option value="Transition">TRANSITION</option>
                                                        <option value="Refund">REFUND</option>
                                                        <option value="Other">OTHER</option>
                                                    </select-->
                                                    <select ng-options="key as value for (key, value) in supprtType" ng-model="balanceRequest.type" class="form-control" id="type" name="type" required>
                                                        <option value="">SELECT TYPE</option>
                                                    </select>
                                                    <p ng-show="balanceRequestFrm.$submitted && balanceRequestFrm.type.$invalid" class="err-mark">Please select a type.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!--label class="control-label"><small>Ticket ID<font color="red"> * (Required)</font></small></lable-->
                                                    <!--input type="text" ng-model="balanceRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isnumber value="{{Auth::user()->id.time()}}" readonly="readonly"-->
                                                    <!--input type="text" name="ticket_id" class="form-control err ng-pristine ng-untouched" value="{{Auth::user()->id.time()}}" readonly="readonly" -->
                                                    <input type="hidden" ng-model="balanceRequest.ticket_id" class="form-control" id="ticket_id" name="ticket_id" ng-init="balanceRequest.ticket_id='{{Auth::user()->id.time()}}'" value="{{Auth::user()->id.time()}}" readonly="readonly" >
                                                     
                                                    <!--p ng-show="balanceRequestFrm.$submitted && (balanceRequestFrm.amount.$invalid || invalidAmount(balanceRequest.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"><small>Branch</small></label>
                                                    <input type="text" ng-model="balanceRequest.branch" class="form-control" name="branch" placeholder="DEPOSIT BRANCH" >
                                                </div>
                                            </div-->
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="control-label"><small>Message<font color="red"> * (Required)</font></small></label>
                                                    <textarea type="text" class="form-control" ng-model="balanceRequest.message" name="message" placeholder="MESSAGE" required></textarea>
                                                    <p ng-show="balanceRequestFrm.$submitted && balanceRequestFrm.message.$invalid" class="err-mark">Please enter your message.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT REQUEST</small></button>
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
        console.log({{ json_encode($support_data) }});
         console.log({{ json_encode(Config::get('dictionary.MODE_OF_TRANSFER')) }});
        angular.module('DIPApp')
                .controller('WalletRequestCtrl', ['$scope', '$http', function ($scope, $http) {

                window.s = $scope

                        $scope.modeOfTransferDictS = {{ json_encode(Config::get('dictionary.MODE_OF_TRANSFER')) }}
                $scope.supprtType = {{ json_encode($support_data) }}

                $scope.submit = submit
                        $scope.invalidAmount = invalidAmount

                        function submit (balanceRequest) {
                            console.log($scope.balanceRequestFrm.$invalid);
                            console.log(invalidAmount(balanceRequest.ticket_id));
                        if ($scope.balanceRequestFrm.$invalid || invalidAmount(balanceRequest.ticket_id)) return
                                req = Object.assign(balanceRequest)
                                console.log(req)
                                 console.log(1)
                                $http.post('/support', req)
                                .then(data => {
                                    
                                toastr.success("Successfully submitted.")
                                        location.href = "support-report"
                                }, fail)
                        }

                function invalidAmount (amount) {
                return amount >= 10 ? false : true
                }

                function fail (err) {
                console.log(err)
                        sweetAlert('Error', 'Something went wrong', 'error')
                }
                }])
    </script>
    @stop
