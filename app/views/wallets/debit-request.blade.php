<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="DebitRequestCtrl" class="head-weight">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider">Wallet Debit Request Form</div>
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body table-responsive">
                <form class="form-signin" name="debitRequestFrm" ng-submit="submit(debitRequest)" novalidate>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==1">Agent</small></label>
                          <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==2">Distributor</small></label>
                        <input type="text" ng-model="debitRequest.child" class="form-control" disabled name="agent" placeholder="Agent" >
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                        <input type="number" ng-model="debitRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isfloat>
                        <p ng-show="debitRequestFrm.$submitted && (debitRequestFrm.amount.$invalid || invalidAmount(debitRequest.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label"><small>Remarks<font color="red"> * (Required)</font></small></label>
                        <input type="text" class="form-control" ng-model="debitRequest.remarks" name="remarks" placeholder="REMARKS" required>
                         <p ng-show="debitRequestFrm.$submitted && (debitRequestFrm.remarks.$invalid && invalidAmount(debitRequestFrm.remarks))" class="err-mark">Please enter remarks.</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-success" disabled name="btn-debit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;DEBIT NOW</small></button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- End Row -->
      </div>
    </div>
  </div>
</div>
@stop
@section('javascript')
<script>
  var child_data={{ json_encode($child) }};
  var dmt_child_data={{ json_encode($child_data) }};
  console.log(dmt_child_data);
angular.module('DIPApp')
  .controller('DebitRequestCtrl', ['$scope', '$http', function ($scope, $http) {
  window.s = $scope;
   $scope.parent_data = {{ json_encode($parent_data) }}
        $scope.child_data=child_data
        $scope.dmt_child_data=dmt_child_data
       $scope.child =  $scope.child_data.name
      $scope.debitRequest = {
        child : $scope.child_data.name,
        child_id : $scope.dmt_child_data.id
      }
  $scope.submit = submit
  $scope.invalidAmount = invalidAmount

  function submit (debitRequest) {
    //if (! confirm ("Are you sure?")) return
    if ($scope.debitRequestFrm.$invalid || invalidAmount(debitRequest.amount)) return
  //  if ($scope.dmt_child_data.balance < debitRequest.amount) return sweetAlert('Error', 'Insufficient Balance.', 'error')

      
        sweetAlert({
      title: "Are you sure?",
      text: "You will not be able to revert this transaction!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, send it!",
      cancelButtonText: "No, cancel it!",
      closeOnConfirm: true,
      closeOnCancel: false
    },
  function(isConfirm) {
    if (isConfirm) {
      req = Object.assign(debitRequest, {user_id: {{Cookie::get('userid')}}})

      $http.post(`/api/v1/users/${dmt_child_data.id}/actions/debit-wallet`, req)
        .then(data => {
             if(data.data == 1)
          {
 oldToastr.success("Agent's account debitted.")
           sweetAlert('Success', "Agent's account debitted.", 'success')
           location.reload();
          }else
          {
             oldToastr.success( "Agent's account debitted.")
           sweetAlert('error',  "Agent's account debitted.", 'error')
           location.reload();
          }

          // sweetAlert('Success', 'Credit sent to the agent.', 'success')
          //location.href = $scope.dmt_child_data.type == 1 ? "/agents" : $scope.dmt_child_data.type == 2 ? "/distributors" : '/'
        }, function (err) {
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Amount is missing or is invalid. Please enter a number', 'error')
            return
          }
          if (err.data.code && (err.data.code == 2 || err.data.code == 3)) {
            sweetAlert('Error', 'The user or you is not a vendor.', 'error')
            return
          }
          if (err.data.code && err.data.code == 4) {
            sweetAlert('Insufficient balance', 'The vendor has insufficient balance.', 'error')
            return
          }
          if (err.status == 403 || err.status == 401) {
            sweetAlert('Error', 'This request is unauthorized.', 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again.', 'error')
        })
    } else {
      sweetAlert("Cancelled", "Your request has been cancelled", "error");
    }
  });
      }

      function invalidAmount (amount) {
        return amount >= 100 && amount <= 2500000 ? false : true
      }

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
}]);
</script>
@stop