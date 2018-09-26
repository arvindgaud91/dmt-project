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
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-areaSalesOfficerSalesDateReport', areaSalesOfficerSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
          @if( $user->vendorDetails->type == 11 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Area Sales Officer Sales Reports Date Wise</a></li>
            </ul>
            @endif
          @if( $user->vendorDetails->type == 10 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Area Sales Officer Sales Reports Date Wise</a></li>
            </ul>
            @endif

           @if( $user->vendorDetails->type == 7 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Area Sales Officer Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( $user->vendorDetails->type == 6 )
            <ul class="nav nav-pills nav-justified">
            <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
            <li><a href="/sales-executive-area-sales-manager-report">Sales Executive Reports</a></li>
            <li class="active"><a>Area Sales Officer Sales Reports Date Wise</a></li>
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
                          <th width='15%'>Area Sales Officer Id</th>
                          <th width='20%'>Area Sales Officer Name</th>
                          <th width='10%'>Sales Executive Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="areaSalesOfficerSale in areaSalesOfficerSales">
                        <td>@{{areaSalesOfficerSale.user_id}}</td>
                        <td><a href="/sales-executive-date-reports-for-area-sales-manager/@{{areaSalesOfficerSale.user_id}}">@{{areasalesofficerName[areaSalesOfficerSale.user_id]}}</a></td>
                        <td>@{{countOfSalesExecutive[areaSalesOfficerSale.user_id]}}</td>
                        <td>@{{areaSalesOfficerFTDSum[areaSalesOfficerSale.user_id]}}</td>
                        <td>@{{areaSalesOfficerLMTDSum[areaSalesOfficerSale.user_id]}}</td>
                        <td>@{{areaSalesOfficerMTDSum[areaSalesOfficerSale.user_id]}}</td>
                      </tr> 
                    </tbody> 
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $areaSalesOfficerSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="areaSalesOfficerSales.length == 0">No Area Sales Officer Sales Report</h4>
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

  var areaSalesOfficerSales = {{json_encode($areaSalesOfficerSales) }}
  var countOfSalesExecutive ={{json_encode($countOfSalesExecutive)}}
  var areaSalesOfficerFTDSum ={{json_encode($areaSalesOfficerFTDSum)}}
  var areaSalesOfficerLMTDSum ={{json_encode($areaSalesOfficerLMTDSum)}}
  var areaSalesOfficerMTDSum = {{json_encode($areaSalesOfficerMTDSum)}}
  var areasalesofficerName = {{json_encode($areasalesofficerName)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.areaSalesOfficerSales = areaSalesOfficerSales
  $scope.countOfSalesExecutive=countOfSalesExecutive
  $scope.areaSalesOfficerFTDSum = areaSalesOfficerFTDSum
  $scope.areaSalesOfficerLMTDSum = areaSalesOfficerLMTDSum
  $scope.areaSalesOfficerMTDSum = areaSalesOfficerMTDSum
  $scope.areasalesofficerName = areasalesofficerName
  console.log($scope.areaSalesOfficerSales)
  console.log($scope.countOfSalesExecutive)
  console.log($scope.areaSalesOfficerFTDSum)
  console.log($scope.areaSalesOfficerLMTDSum)
  console.log($scope.areaSalesOfficerMTDSum)
  console.log($scope.areasalesofficerName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Cluster Head ID': obj.id,
        'Cluster Head Name': obj.name,
        'Area Sales Manager Count': countOfSalesExecutive[obj.id],
        'FTDAmount': areaSalesOfficerFTDSum[obj.id],
        'LMTDAmount': areaSalesOfficerLMTDSum[obj.id],
        'MTDAmount': areaSalesOfficerMTDSum[obj.id]
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


