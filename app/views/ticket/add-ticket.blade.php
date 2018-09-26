<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<style>
/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 1000;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.3);
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}
</style>
  <div ng-controller="AddTicketCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Generate Ticket</div>

          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="addTicketFrm" ng-submit="submit(addTicket)" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Priority<font color="red"> * (Required)</font></small></label>
                          <select class="form-control" ng-model="addTicket.priority" name="priority" required>
                            <option value="">Select Priority</option>
                            <option value="Low">Low</option>
                            <option value="Moderate">Moderate</option>
                            <option value="High">High</option>
                          </select>
                          <p ng-show="addTicketFrm.$submitted && addTicketFrm.priority.$invalid" class="err-mark">Please select priority.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Product<font color="red"> * (Required)</font></small></label>
                          <select class="form-control" ng-model="addTicket.product" name="product" ng-change="getIssue()" required>
                            <option value="">Select Product</option>
                            <!-- <option value="AEPS">AEPS</option> -->
                            <option value="DMT">DMT</option>
                            <!-- <option value="Prepaid Card">Prepaid Card</option> -->
                          </select>
                          <p ng-show="addTicketFrm.$submitted && addTicketFrm.product.$invalid" class="err-mark">Please select product.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Issue 1<font color="red"> * (Required)</font></small></label>
                          <select class="form-control" ng-show="!issue.length>0" ng-model="addTicket.issue1" name="issue1" required>
                            <option value=""></option>
                          </select>
                          <select class="form-control" ng-show="issue.length>0" ng-model="addTicket.issue1" ng-options="option.problem_name for option in issue track by option.problem_id" name="issue1" ng-change="getIssueList()" required>
                            <option value="">Select Issue 1</option>
                          </select>
                          <p ng-show="addTicketFrm.$submitted && addTicketFrm.issue1.$invalid" class="err-mark">Please select issue 1.</p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Issue 2<font color="red"> * (Required)</font></small></label>
                          <select class="form-control" ng-show="!issuelist.length>0" ng-model="addTicket.issue2" name="issue2" disabled="">
                            <option value=""></option>
                          </select>
                          <select class="form-control" ng-show="issuelist.length>0" ng-model="addTicket.issue2" ng-options="option.issue_name for option in issuelist track by option.issue_id" name="issue2">
                            <option value="">Select Issue 2</option>
                          </select>
                          <!-- <p ng-show="addTicketFrm.$submitted && addTicketFrm.issue2.$invalid" class="err-mark">Please select issue 2.</p> -->
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Issue Description<font color="red"> * (Required)</font></small></label>
                            <!-- <input type="text" class="form-control" ng-model="addTicket.comment" name="comment" placeholder="COMMENT" required> -->
                            <textarea class="form-control" ng-maxlength="1000" ng-minlength="10" ng-model="addTicket.comment" name="comment" placeholder="ISSUE DESCRIPTION" required></textarea>
                            <p ng-show="addTicketFrm.$submitted && addTicketFrm.comment.$error.required" class="err-mark">Please enter issue description.</p>
                            <p ng-show="addTicketFrm.comment.$error.maxlength" class="err-mark">Entered issue description is too long.It need to be 10 to 1000 characters.</p>
                            <p ng-show="addTicketFrm.comment.$error.minlength" class="err-mark">Entered issue description is too short.It need to be 10 to 1000 characters.</p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit"  class="btn btn-success" name="btn-credit-request" ng-disabled="disabled"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;GENERATE TICKET</small></button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End Row -->

          <!-- add customer cron code test -->
          <!-- <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="addCustomerFrm" ng-submit="addCustomer()" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit"  class="btn btn-success" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;ADD CUSTOMER</small></button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div> -->
          <!-- End Row -->
        </div>
      </div>
    </div>
    <div class="loading" ng-show="loading"> 
      <img src="/images/loading1.gif" alt="Loading..."/>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('AddTicketCtrl', ['$scope', '$http', function ($scope, $http) {

      //window.s = $scope;
      $scope.disabled= false;
      $scope.submit = submit;
      $scope.addCustomer = addCustomer;
      $scope.getIssue = getIssue;
      $scope.getIssueList = getIssueList;

      function getIssue()
      {
        $scope.loading = true;
        if($scope.addTicket.product != "" && $scope.addTicket.product != undefined)
        {
          // alert($scope.addTicket.product);
          $http.post("/api/v1/ticket/product-issue-list/" + $scope.addTicket.product)
          .then(data => {
            $scope.issue = data.data.data
            $scope.loading = false;
            // console.log($scope.issue)
          }, function (err) {
            if (err.data.code && err.data.code !== 200) {
              sweetAlert('Error', 'Error. Try again.', 'error')
              return
            }
            sweetAlert('Error', 'Error. Try again.', 'error')
          })
        }
        else
        {
          $scope.issue = '';
        }
      }

      function getIssueList()
      {
        $scope.issuelist = $scope.addTicket.issue1.issue_list;
        // console.log($scope.issuelist);
      }

      function submit (addTicket) {    
        if ($scope.addTicketFrm.$invalid) return
        $scope.disabled= true;

        // req = Object.assign(addTicket)
        if($scope.addTicket.issue2 == undefined)
        {
          req = Object.assign(addTicket,{issue1: $scope.addTicket.issue1.problem_id,issue2: ""})
        }
        else
        {
          req = Object.assign(addTicket,{issue1: $scope.addTicket.issue1.problem_id,issue2: $scope.addTicket.issue2.issue_id})
        }
        

        $http.post(`/api/v1/ticket/add-ticket`, req)
        .then(data => {
          // console.log(data.data);
          // oldToastr.success("Ticket has been generated successfully.")
          sweetAlert('Success', data.data, 'success')
          location.href = '/all-ticket'
        }, function (err) {
          if (err.data.code && err.data.code !== 200) {
            sweetAlert('Error', 'Error. Try again1.', 'error')
            return
          }
          // console.log(err);
          if(err.status == 422)
          {
            sweetAlert('Error', err.data, 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again2.', 'error')
        })
      }

      function addCustomer () {    
        $http.post(`/api/v1/ticket/add-customer`)
        .then(data => {
          // console.log(data);
          // oldToastr.success("Ticket has been generated successfully.")
          sweetAlert('Success', data.data, 'success')
        }, function (err) {
          if (err.data.code && err.data.code !== 200) {
            sweetAlert('Error', 'Error. Try again.', 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again.', 'error')
        })
      }

      
      function fail (err) {
        
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
