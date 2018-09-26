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
            <li class="active"><a>Regional Head Sales Reports</a></li>
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-stateHeadSale', stateHeadSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
         <ul class="nav nav-pills nav-justified">
            <li class="active"><a>State Head Sales Reports</a></li>
            <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
            <li><a href="/state-head-regional-head-date-report">State Head Sales Reports Date Wise</a></li>
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
                          <th width='20%'>State Head ID</th>
                          <th width='20%'>State Head Name</th>
                          <th width='20%'>Cluster Head Count</th> 
                          <th width='20%'>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="stateHeadSale in stateHeadSales">
                      <td>@{{stateHeadSale.user_id}}</td>
                        <td><a href="/cluster-head-reports-for-state-head/@{{stateHeadSale.user_id}}">@{{stateheadName[stateHeadSale.user_id]}}</a></td>
                        <td>@{{countOfClusterHead[stateHeadSale.user_id]}}</td>
                        <td>@{{sumOfAgentAmount[stateHeadSale.user_id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $stateHeadSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="stateHeadSales.length == 0">No State Head Sales Report</h4>
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
  var stateHeadSales = {{json_encode($stateHeadSales) }}
  var countOfClusterHead = {{json_encode($countOfClusterHead)}}
  var sumOfAgentAmount = {{json_encode($sumOfAgentAmount)}}
  var stateheadName = {{json_encode($stateheadName)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  
  $scope.stateHeadSales = stateHeadSales
  $scope.countOfClusterHead = countOfClusterHead
  $scope.sumOfAgentAmount = sumOfAgentAmount
  $scope.stateheadName = stateheadName
  console.log($scope.stateHeadSales)
  console.log($scope.countOfClusterHead)
  console.log($scope.sumOfAgentAmount)
  console.log($scope.stateheadName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Distributor_ID': obj.id,
        'Distributor_Name': obj.name,
        'Agent_Count': countOfClusterHead[obj.id],
        'Total_Amount': sumOfAgentAmount[obj.id]
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