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
            <li class="active"><a>Area Sales Manager Reports</a></li>
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-salesExecutiveSale', salesExecutiveSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
         <ul class="nav nav-pills nav-justified">
            <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
            <li class="active"><a>Sales Executive Reports</a></li>
            <li><a href="/sales-executive-area-sales-officer-date-report">Area Sales Officer Sales Reports Date Wise</a></li>
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
                          <th width='20%'>Sales Executive ID</th>
                          <th width='20%'>Sales Executive Name</th>
                          <!-- <th width='20%'>Mobile No</th> -->
                          <th width='20%'>Distributor Count</th>
                          <th width='20%'>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="salesExecutiveSale in salesExecutiveSales">
                      <td>@{{salesExecutiveSale.user_id}}</td>
                        <td><a href="/sales-executive-reports-for-area-sales-officer/@{{salesExecutiveSale.user_id}}">@{{salesexecutiveName[salesExecutiveSale.user_id]}}</a></td>
                        <!-- <td>@{{salesExecutiveSale.phone_no}}</td> -->
                        <td>@{{countOfDistributor[salesExecutiveSale.user_id]}}</td>
                        <td>@{{sumOfAgentAmount[salesExecutiveSale.user_id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $salesExecutiveSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="salesExecutiveSales.length == 0">No Sales Executive Sales Report</h4>
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
  var salesExecutiveSales = {{json_encode($salesExecutiveSales) }}
  var countOfDistributor ={{json_encode($countOfDistributor)}}
  var sumOfAgentAmount = {{json_encode($sumOfAgentAmount)}}
  var salesexecutiveName = {{json_encode($salesexecutiveName)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  
  $scope.salesExecutiveSales = salesExecutiveSales
  $scope.countOfDistributor=countOfDistributor
  $scope.sumOfAgentAmount = sumOfAgentAmount
  $scope.salesexecutiveName = salesexecutiveName

  console.log($scope.salesExecutiveSales)
  console.log($scope.countOfDistributor)
  console.log($scope.sumOfAgentAmount)
  console.log($scope.salesexecutiveName)
  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Sales Executive ID': obj.id,
        'Sales Executive Name': obj.name,
        'Distributor Count': countOfDistributor[obj.id],
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
