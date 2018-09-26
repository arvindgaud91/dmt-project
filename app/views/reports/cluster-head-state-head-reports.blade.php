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
            <li class="active"><a>State Head Sales Reports</a></li>
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-clusterHeadSale', clusterHeadSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
         <ul class="nav nav-pills nav-justified">
            <li class="active"><a>Cluster Head Sales Reports</a></li>
            <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
            <li><a href="/cluster-head-state-head-date-report">Cluster Head Sales Reports Date Wise</a></li>
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
                          <th width='20%'>Cluster Head ID</th>
                          <th width='20%'>Cluster Head Name</th>
                          <th width='20%'>Area Sales Manager Count</th>
                          <th width='20%'>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="clusterHeadSale in clusterHeadSales">
                      <td>@{{clusterHeadSale.user_id}}</td>
                        <td><a href="/area-sales-manager-reports-for-cluster-head/@{{clusterHeadSale.user_id}}">@{{clusterheadName[clusterHeadSale.user_id]}}</a></td>
                        <td>@{{countOfSalesManager[clusterHeadSale.user_id]}}</td>
                        <td>@{{sumOfAgentAmount[clusterHeadSale.user_id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $clusterHeadSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="clusterHeadSales.length == 0">No Cluster Head Sales Report</h4>
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
  var clusterHeadSales = {{json_encode($clusterHeadSales) }}
  var countOfSalesManager = {{json_encode($countOfSalesManager)}}
  var sumOfAgentAmount = {{json_encode($sumOfAgentAmount)}}
  var clusterheadName = {{json_encode($clusterheadName)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  
  $scope.clusterHeadSales = clusterHeadSales
  $scope.countOfSalesManager = countOfSalesManager
  $scope.sumOfAgentAmount = sumOfAgentAmount
  $scope.clusterheadName = clusterheadName
  console.log($scope.clusterHeadSales)
  console.log($scope.countOfSalesManager)
  console.log($scope.sumOfAgentAmount)
  console.log($scope.clusterheadName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Cluster Head ID': obj.id,
        'Cluster Head Name': obj.name,
        'Area Sales Manager Count': countOfSalesManager[obj.id],
        'Total Amount': sumOfAgentAmount[obj.id]
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