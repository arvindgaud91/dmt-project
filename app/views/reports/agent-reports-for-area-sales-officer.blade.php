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
        <div class='col-md-2'>
          <button ng-click="exportFile('dmt-agentSales', agentSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
        </div>
      </div>
         <ul class="nav nav-pills nav-justified">
            @if( $user->vendorDetails->type == 11 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/state-head-regional-head-date-report">State Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( $user->vendorDetails->type == 10 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/cluster-head-state-head-date-report">Cluster Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( $user->vendorDetails->type == 7 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/area-sales-manager-cluster-head-date-report">Area Sales Manager Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( $user->vendorDetails->type == 6 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/area-sales-officer-area-sales-manager-date-report">Area Sales Officer Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( $user->vendorDetails->type == 5 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
            <li class="active"><a>Agent Sales Reports</a></li>
            <li><a href="/sales-executive-area-sales-officer-date-report">Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
            
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
                          <th>Agent Id</th>
                          <th>Agent Name</th>
                          <th>Code</th>
                          <th>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="agentSale in agentSales">
                        <td>@{{agentSale.user_id}}</td>
                        <td>@{{agentName[agentSale.user_id]}}</td>
                        <td>@{{agentSale.bc_agent}}</td>
                        <td>@{{agentSum[agentSale.user_id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $agentSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="distributorSales.length == 0">No Agent Sales Report</h4>
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

  var agentSales = {{json_encode($agentSales) }}
  var agentSum = {{json_encode($agentSum) }}
  var parentId = {{json_encode($parentId)}}
  var agentName = {{json_encode($agentName)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  $scope.agentSales = agentSales
  $scope.agentSum = agentSum
  $scope.parentId = parentId
  $scope.agentName = agentName
  console.log($scope.agentSales)
  console.log($scope.agentSum)
  console.log($scope.parentId)
  console.log($scope.agentName)
  $scope.exportFile = exportFile
  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Agent Id': obj.user_id,
        'Agent Name': agentName[obj.user_id],
        'Code': obj.bc_agent,
        'Total Amount': agentSum[obj.user_id]
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


