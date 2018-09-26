<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<?php $user = Auth::user(); ?>
<div ng-controller="SalesReportCtrl" id="page-wrapper">
  <div style="height: 40px;"></div>
  <div class="row">
    <div class="col-lg-12">
     
      <div class="panel panel-default">
        <div class="panel-body">
          <ul class="nav nav-pills nav-justified">
            @if( $user->vendorDetails->type == 11 )
            <li class="active"><a>Regional Head Sales Reports</a></li>
            @endif
            @if( $user->vendorDetails->type == 10 )
            <li class="active"><a>State Head Sales Reports</a></li>
            @endif
            @if( $user->vendorDetails->type == 7 )
            <li class="active"><a>Cluster Head Sales Reports</a></li>
            @endif
            @if( $user->vendorDetails->type == 6 )
            <li class="active"><a>Area Sales Manager Sales Reports</a></li>
            @endif
            @if( $user->vendorDetails->type == 5 )
            <li class="active"><a>Area Sales Officer Sales Reports</a></li>
            @endif
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-salesExecutiveSalesReportDateWise', salesExecutiveSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
          @if( $user->vendorDetails->type == 11 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
          @if( $user->vendorDetails->type == 10 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
          @if( $user->vendorDetails->type == 7 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( $user->vendorDetails->type == 6 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
              <li><a href="/sales-executive-area-sales-manager-report">Sales Executive Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( $user->vendorDetails->type == 5 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
            <li><a href="distributor-area-sales-report">Distributor Sales Reports</a></li>
            <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
        <div class="panel panel-default">
        
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="clearfix"></div>
              <div class="table-responsive">
                    <table id="sales" class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th width='15%'>Sales Executive Id</th>
                          <th width='20%'>Sales Executive Name</th>
                          <th width='10%'>Distributor Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="salesExecutiveSale in salesExecutiveSales">
                        <td>@{{salesExecutiveSale.user_id}}</td>
                        <td><a href="/distributor-date-report-for-area-sales-officer/@{{salesExecutiveSale.user_id}}">@{{salesExecutiveName[salesExecutiveSale.user_id]}}</a></td>
                        <td>@{{countOfDistributor[salesExecutiveSale.user_id]}}</td>
                        <td>@{{salesExecutiveFTDSum[salesExecutiveSale.user_id]}}</td>
                        <td>@{{salesExecutiveLMTDSum[salesExecutiveSale.user_id]}}</td>
                        <td>@{{salesExecutiveMTDSum[salesExecutiveSale.user_id]}}</td>
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
  var salesExecutiveFTDSum ={{json_encode($salesExecutiveFTDSum)}}
  var salesExecutiveLMTDSum ={{json_encode($salesExecutiveLMTDSum)}}
  var salesExecutiveMTDSum = {{json_encode($salesExecutiveMTDSum)}}
  var salesExecutiveName = {{json_encode($salesExecutiveName) }}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.salesExecutiveSales = salesExecutiveSales
  $scope.countOfDistributor=countOfDistributor
  $scope.salesExecutiveFTDSum = salesExecutiveFTDSum
  $scope.salesExecutiveLMTDSum = salesExecutiveLMTDSum
  $scope.salesExecutiveMTDSum = salesExecutiveMTDSum
  $scope.salesExecutiveName= salesExecutiveName

  console.log($scope.salesExecutiveSales)
  console.log($scope.countOfDistributor)
  console.log($scope.salesExecutiveFTDSum)
  console.log($scope.salesExecutiveLMTDSum)
  console.log($scope.salesExecutiveMTDSum)
  console.log($scope.salesExecutiveName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Sales Executive Id': obj.id,
        'Sales Executive Name': obj.name,
        'Distributor Count': countOfDistributor[obj.id],
        'FTDAmount': salesExecutiveFTDSum[obj.id],
        'LMTDAmount': salesExecutiveLMTDSum[obj.id],
        'MTDAmount': salesExecutiveMTDSum[obj.id]
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


