<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight">
    <div class="row">
        <div class="col-md-6 col-md-offset-3"  id="section-to-print">
            <div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider">
                    Transaction  Receipt <i style="float: right; cursor: pointer;" class="icon mdi mdi-print" onclick="window.print();"></i>
                    <div class="clearfix"></div>
                </div>
                @if($domain_data == 'dmt.mysuravi.com:8000' || $domain_data == 'dmt.lrprch.in:8000' || $domain_data == 'dmt.manjilgroup.com:8000' || $domain_data == 'dmt.indiapaysolution.com:8000' || $domain_data == 'dmt.samriddhifoundation.net.in:8000' || $domain_data == 'dmt.samriddhifoundation.net.in:8000' || $domain_data == 'dmtservices.primagemarketing.com:8000' || $domain_data == 'dmt.amenitiesservices.in:8000' || $domain_data == 'dmt.ekiosk.in:8000' || $domain_data == 'dmt.aonehub.com:8000' || $domain_data == 'dmt.zippays.in:8000' || $domain_data == 'dmtpearltek.digitalindiapayments.com:8000' || $domain_data == 'dmt.akpayments.in:8000' || $domain_data == 'dmt.oneindiapayments.com:8000')
                <img src="/images/_blank.png" height="50px;" width="50px;" style="margin-left: 20px;">
                @else
                <img src="/images/cinqueterre.png" height="50px;" width="50px;" style="margin-left: 20px;">
                @endif
                <img src="/images/rbl.png" align="right" height="50px;" style="margin-right:15px;">

                <div class="panel-body">
                    <form style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                        <table class="table table-bordered">
                            <thead>
                                
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Transaction ID:</td>
                                    <td>@{{transactions.order_id}}</td>
                                </tr>
                                <tr>
                                    <td>Paytm Transaction ID:</td>
                                    <td>@{{transactions.bank_tx_id}}</td>
                                </tr>
                               
                                <tr>
                                    <td>Transaction Amount: </td>
                                    <td>@{{transactions.amount}}</td>
                                </tr>
                                <tr>
                                    <td>Transaction Status: </td>
                                    <td>@{{transactions.status}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Transaction Response code: </td>
                                    <td>@{{transactions.response_code}}</td>
                                </tr>
                        
                                <tr>
                                    <td>Date &amp; Time Of Transaction:</td>
                                    <td>@{{transactions.created_at | date: 'medium'}}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script>
    var transactions = {{json_encode($transactions) }}

    
</script>
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
    window.s = $scope
     $scope.transactions = transactions
    // $scope.exportFile = exportFile

    // function formatTransaction (tx) {
    //  tx.created_at = new Date(tx.created_at)
    //  return tx
    // }
        function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
    }
}])
</script>
@stop