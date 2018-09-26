<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<style>
.p-low {
    background: #ff0000;
    color: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    padding: 2px 5px;
    font-weight: 600;
}
.p-high {
    background: #008000;
    color: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    padding: 2px 5px;
    font-weight: 600;
}
.p-moderate {
    background: #ffa500;
    color: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    padding: 2px 5px;
    font-weight: 600;
}
.p-open {
    background: #ff0000;
    color: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    padding: 2px 5px;
    font-weight: 600;
}
.p-closed {
    background: #008000;
    color: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    padding: 2px 5px;
    font-weight: 600;
}
.p-other {
    background: #ffa500;
    color: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    padding: 2px 5px;
    font-weight: 600;
}
</style>
<div ng-controller="AllTicketCtrl" class="head-weight">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider">Tickets</div>
        <br>
        <div class="panel-body">
          <div class="row" ng-show="tickets.length>0">
            <div class="col-md-12">
              <button ng-click="exportFile('all-support-tickets', tickets)" class="btn btn-primary" style="float: right;">Export as excel</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default panel-table">
                <div class="panel-body table-responsive">
                  <table class="table table-striped table-borderless" ng-show="tickets.length>0">
                    <thead>
                      <tr>
                        <th>Sr. No.</th>
                        <th></th>
                        <th>Ticket Number</th>
                        <th>Priority</th>
                        <th>Product</th>
                        <th>Issue</th>
                        <th>Created At</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="ticket in tickets | orderBy:'$index':true">
                        <td>@{{$index + 1}}</td>
                        <td><a href="/view-ticket/@{{ticket.ticket_no}}"><i class="fa fa-eye" style="font-size:25px;color:green"></i></a></td>
                        <td>@{{ticket.ticket_no}}</td>
                        <td ng-if="ticket.priority == 'low'"><span class="p-low">Low</span></td>
                        <td ng-if="ticket.priority == 'high'"><span class="p-high">High</span></td>
                        <td ng-if="ticket.priority == 'moderate'"><span class="p-moderate">Moderate</span></td>
                        <td>@{{ticket.product}}</td>
                        <td>@{{ticket.issue1}}</td>
                        <td>@{{ticket.created_date | dateToISO | date:'medium'}}</td>
                        <!-- <td>@{{ticket.status}}</td> -->
                        <td ng-if="ticket.status == 'Open'"><span class="p-open">@{{ticket.status}}</span></td>
                        <td ng-if="ticket.status == 'Closed'"><span class="p-closed">@{{ticket.status}}</span></td>
                        <td ng-if="ticket.status == 'Suspended'"><span class="p-other">@{{ticket.status}}</span></td>
                      </tr>
                    </tbody>
                  </table>
                  <h5 ng-hide="tickets.length > 0">No ticket generated yet.</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-12">
            <div class="widget widget-calendar">
              <div id="calendar-widget"></div>
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
  var tickets = {{json_encode($tickets) }};
</script>
<script>
angular.module('DIPApp')
.controller('AllTicketCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {

  $scope.tickets = {{$tickets}};

  console.log($scope.tickets);

  $scope.exportFile = exportFile
  

    function exportFile (filename, data) {
      $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      
        newObj = {
          'Ticket_No': obj.ticket_no ,
          'Priority': obj.priority ,
          'Product': obj.product,
          'Issue 1': obj.issue1,
          'Issue 2': obj.issue2,
          'Issue Description': obj.comment,
          'Created_at': obj.created_date
        }
        
        return newObj
      })}).then(function (data) {
        window.location.href = '/exports/'+data.data+'.xls'
      }, console.log)
    }


  function fail (err) {
    
    sweetAlert('Error', 'Something went wrong', 'error')
  }
}])
</script>
@stop