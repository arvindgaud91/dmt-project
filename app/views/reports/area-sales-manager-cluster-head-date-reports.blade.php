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
          </ul> 
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('dmt-areaSalesManagerSalesDateReport', areaSalesManagerSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
            @if( $user->vendorDetails->type == 11 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Area Sales Manager Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( $user->vendorDetails->type == 10 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Area Sales Manager Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( $user->vendorDetails->type == 7 )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Area Sales Manager Sales Reports Date Wise</a></li>
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
                          <th width='15%'>Area Sales Manager ID</th>
                          <th width='20%'>Area Sales Manager Name</th>
                          <th width='10%'>Area Sales Officer Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="areaSalesManagerSale in areaSalesManagerSales">
                        <td>@{{areaSalesManagerSale.user_id}}</td>
                        <td><a href="/area-sales-officer-date-reports-for-cluster-head/@{{areaSalesManagerSale.user_id}}">@{{areaSalesManagerName[areaSalesManagerSale.user_id]}}</a></td>
                        <td>@{{countOfSalesOfficer[areaSalesManagerSale.user_id]}}</td>
                        <td>@{{areaSalesManagerFTDSum[areaSalesManagerSale.user_id]}}</td>
                        <td>@{{areaSalesManagerLMTDSum[areaSalesManagerSale.user_id]}}</td>
                        <td>@{{areaSalesManagerMTDSum[areaSalesManagerSale.user_id]}}</td>
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
  var countOfSalesOfficer ={{json_encode($countOfSalesOfficer)}}
  var areaSalesManagerFTDSum ={{json_encode($areaSalesManagerFTDSum)}}
  var areaSalesManagerLMTDSum ={{json_encode($areaSalesManagerLMTDSum)}}
  var areaSalesManagerMTDSum = {{json_encode($areaSalesManagerMTDSum)}}
  var areaSalesManagerName = {{json_encode($areaSalesManagerName)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.areaSalesManagerSales = areaSalesManagerSales
  $scope.countOfSalesOfficer=countOfSalesOfficer
  $scope.areaSalesManagerFTDSum = areaSalesManagerFTDSum
  $scope.areaSalesManagerLMTDSum = areaSalesManagerLMTDSum
  $scope.areaSalesManagerMTDSum = areaSalesManagerMTDSum
  $scope.areaSalesManagerName = areaSalesManagerName
  console.log($scope.areaSalesManagerSales)
  console.log($scope.countOfSalesOfficer)
  console.log($scope.areaSalesManagerFTDSum)
  console.log($scope.areaSalesManagerLMTDSum)
  console.log($scope.areaSalesManagerMTDSum)
  console.log($scope.areaSalesManagerName)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Cluster Head ID': obj.id,
        'Cluster Head Name': obj.name,
        'Area Sales Manager Count': countOfSalesOfficer[obj.id],
        'FTDAmount': areaSalesManagerFTDSum[obj.id],
        'LMTDAmount': areaSalesManagerLMTDSum[obj.id],
        'MTDAmount': areaSalesManagerMTDSum[obj.id]
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


