<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')

<div ng-controller="SalesReportCtrl" id="page-wrapper">
  <div style="height: 40px;"></div>
  <div class="row">
    <div class="col-lg-12">
     
      <div class="panel panel-default">
        <div class="panel-body">
          <ul class="nav nav-pills nav-justified">
            <li class="active"><a>Sales Reports</a></li>
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-distributorSaleDateReport', distributorSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
         <ul class="nav nav-pills nav-justified">
            <li><a href="/dmt-distributor-sales-report">Distributor Sales Reports</a></li>
            <li><a href="/dmt-agent-sales-report">Agent Sales Reports</a></li>
            <li class="active"><a>Distributor Sales Reports Date Wise</a></li>
          </ul>
        <div class="panel panel-default">
        
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="clearfix"></div>
              <div class="table-responsive">
                    <table id="sales" class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th width='20%'>Distributor Id</th>
                          <th width='20%'>Distributor Name</th>
                          <!-- <th width='10%'>Mobile No</th> -->
                          <th width='10%'>Agent Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="distributorSale in distributorSales">
                        <td>@{{distributorSale.user_id}}</td>
                        <td><a href="/agent-distributor-date-report-for-area-sales-officer/@{{distributorSale.user_id}}">@{{distributorName[distributorSale.user_id]}}</td>
                        <!-- <td>@{{distributorPhone[distributorSale.user_id]}}</td> -->
                        <td>@{{distributorAgentCount[distributorSale.id]}}</td>
                        <td>@{{distributorAgentFTDSum[distributorSale.id]}}</td>
                        <td>@{{distributorAgentLMTDSum[distributorSale.id]}}</td>
                        <td>@{{distributorAgentMTDSum[distributorSale.id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $distributorSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="distributorSales.length == 0">No Distributor Sales Report</h4>
                </div>
              </div>
            </div>
          </div>
          <!-- End Row -->
        </div>
      </div>
    </div>
  </div>
</div>
@stop
@section('javascript')
<script>

  var distributorSales = {{json_encode($distributorSales) }}
  var distributorAgentCount ={{json_encode($distributorAgentCount)}}
  var distributorAgentFTDSum ={{json_encode($distributorAgentFTDSum)}}
  var distributorAgentLMTDSum ={{json_encode($distributorAgentLMTDSum)}}
  var distributorAgentMTDSum ={{json_encode($distributorAgentMTDSum)}}
  var distributorName = {{json_encode($distributorName) }}
  var distributorPhone = {{json_encode($distributorPhone) }}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.distributorSales = distributorSales
  $scope.distributorAgentCount=distributorAgentCount
  $scope.distributorAgentFTDSum=distributorAgentFTDSum
  $scope.distributorAgentLMTDSum=distributorAgentLMTDSum
  $scope.distributorAgentMTDSum=distributorAgentMTDSum
  console.log($scope.distributorSales)
  console.log($scope.distributorAgentCount)
  console.log($scope.distributorAgentFTDSum)
  console.log($scope.distributorAgentLMTDSum)
  console.log($scope.distributorAgentMTDSum)
  $scope.distributorName= distributorName
  console.log(distributorName)
  $scope.distributorPhone= distributorPhone
  console.log(distributorPhone)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Distributor Id': obj.user_id,
        'Distributor Name': distributorName[obj.user_id],
        'Agent Count': distributorAgentCount[obj.id],
        'FTDAmount': distributorAgentFTDSum[obj.id],
        'LMTDAmount ': distributorAgentLMTDSum[obj.id],
        'MTDAmount': distributorAgentMTDSum[obj.id]
      }
      return newObj
    })}).then(function (data) {
      window.location.href = '/exports/'+data.data+'.xls'
    }, console.log)
  }

 
  function fail (err) {
    console.log(err)
    sweetAlert('Error', 'Something went wrong', 'error')
  }
}])
</script>
@stop


