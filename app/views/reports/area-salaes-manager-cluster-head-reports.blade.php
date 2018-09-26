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
            <li class="active"><a>Cluster Head Sales Reports</a></li>
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-areaSalesManagerSale', areaSalesManagerSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
         <ul class="nav nav-pills nav-justified">
            <li class="active"><a>Area Sales Manager Sales Reports</a></li>
            <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Officer Sales Reports</a></li>
            <li><a href="/area-sales-manager-cluster-head-date-report">Area Sales Manager Sales Reports Date Wise</a></li>
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
                          <th width='20%'>Area Sales Manager ID</th>
                          <th width='20%'>Area Sales Manager Name</th>
                          <th width='20%'>Area Sales Officer Count</th>
                          <th width='20%'>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="areaSalesManagerSale in areaSalesManagerSales">
                      <td>@{{areaSalesManagerSale.user_id}}</td>
                        <td><a href="/area-sales-officer-for-clustor-head/@{{areaSalesManagerSale.user_id}}">@{{areaSalesManagerName[areaSalesManagerSale.user_id]}}</a></td>
                        <td>@{{countOfSalesOfficer[areaSalesManagerSale.user_id]}}</td>
                        <td>@{{sumOfAgentAmount[areaSalesManagerSale.user_id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $areaSalesManagerSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="areaSalesManagerSales.length == 0">No Area Sales Manager Sales Report</h4>
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
  var areaSalesManagerSales = {{json_encode($areaSalesManagerSales) }}
  var countOfSalesOfficer = {{json_encode($countOfSalesOfficer)}}
  var sumOfAgentAmount = {{json_encode($sumOfAgentAmount)}}
  var areaSalesManagerName = {{json_encode($areaSalesManagerName)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  
  $scope.areaSalesManagerSales = areaSalesManagerSales
  $scope.countOfSalesOfficer = countOfSalesOfficer
  $scope.sumOfAgentAmount = sumOfAgentAmount
  $scope.areaSalesManagerName = areaSalesManagerName
  console.log($scope.areaSalesManagerSales)
  console.log($scope.countOfSalesOfficer)
  console.log($scope.sumOfAgentAmount)
  console.log($scope.areaSalesManagerName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Area Sales Manager ID': obj.id,
        'Area Sales Manager Name': obj.name,
        'Area Sales Officer Count': countOfSalesOfficer[obj.id],
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


