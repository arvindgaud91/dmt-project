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
            <button ng-click="exportFile('dmt-distributorSalesReportDateWise', distributorSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
          @if( $user->vendorDetails->type == 11 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Distributor Reports Date Wise</a></li>
            </ul>
            @endif
          @if( $user->vendorDetails->type == 10 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Distributor Reports Date Wise</a></li>
            </ul>
            @endif
          @if( $user->vendorDetails->type == 7 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Distributor Reports Date Wise</a></li>
            </ul>
            @endif
           @if( $user->vendorDetails->type == 6 )
            <ul class="nav nav-pills nav-justified">
            <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
            <li><a href="/sales-executive-area-sales-manager-report">Sales Executive Reports</a></li>
            <li class="active"><a>Distributor Reports Date Wise</a></li>
          </ul>
            @endif
            @if( $user->vendorDetails->type == 5 )
            <ul class="nav nav-pills nav-justified">
            <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
            <li><a href="/distributor-area-sales-report">Distributor Sales Reports</a></li>
            <li class="active"><a>Distributor Reports Date Wise</a></li>
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
                          <th width='15%'>Distributor Id</th>
                          <th width='20%'>Distributor Name</th>
                          <th width='10%'>Agent Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="distributorSale in distributorSales">
                        <td>@{{distributorSale.user_id}}</td>
                        <td><a href="/agent-distributor-date-report-for-area-sales-officer/@{{distributorSale.user_id}}">@{{distributorName[distributorSale.user_id]}}</a></td>
                        <td>@{{distributorAgentCount[distributorSale.user_id]}}</td>
                        <td>@{{distributorFTDSum[distributorSale.user_id]}}</td>
                        <td>@{{distributorLMTDSum[distributorSale.user_id]}}</td>
                        <td>@{{distributorMTDSum[distributorSale.user_id]}}</td>
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
  var distributorFTDSum ={{json_encode($distributorFTDSum)}}
  var distributorLMTDSum ={{json_encode($distributorLMTDSum)}}
  var distributorMTDSum ={{json_encode($distributorMTDSum)}}
  var distributorName = {{json_encode($distributorName)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.distributorSales = distributorSales
  $scope.distributorAgentCount=distributorAgentCount
  $scope.distributorFTDSum=distributorFTDSum
  $scope.distributorLMTDSum=distributorLMTDSum
  $scope.distributorMTDSum=distributorMTDSum
  $scope.distributorName=distributorName
  console.log($scope.distributorSales)
  console.log($scope.distributorAgentCount)
  console.log($scope.distributorFTDSum)
  console.log($scope.distributorLMTDSum)
  console.log($scope.distributorMTDSum)
  console.log($scope.distributorName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Sales Executive Id': obj.id,
        'Sales Executive Name': obj.name,
        'Distributor Count': distributorAgentCount[obj.id],
        'FTDAmount': distributorFTDSum[obj.id],
        'LMTDAmount': distributorLMTDSum[obj.id],
        'MTDAmount': distributorMTDSum[obj.id]
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


