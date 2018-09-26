<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="CreditRequestCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Wallet Credit Request Form</div>
        
            <div class="panel-body">
                <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="creditRequestFrm" ng-submit="submit(creditRequest)" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==1">Agent</small></label>
                          <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==2">Distributor</small></label>
                          <input type="text" ng-model="creditRequest.child" class="form-control" disabled name="agent" placeholder="Agent" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                          <input type="number" ng-model="creditRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isfloat>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.amount.$invalid || invalidAmount(creditRequest.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Remarks<font color="red"> * (Required)</font></small></label>
                          <input type="text" class="form-control" ng-model="creditRequest.remarks" name="remarks" placeholder="REMARKS" required>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.remarks.$invalid && invalidAmount(creditRequestFrm.remarks))" class="err-mark">Please enter remarks.</p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT REQUEST</small></button>
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
  </div>
@stop

@section('javascript')
<script>
  var child_data={{ json_encode($child) }};
  var dmt_child_data={{ json_encode($child_data) }};
 console.log(child_data)
  angular.module('DIPApp')
    .controller('CreditRequestCtrl', ['$scope', '$http', function ($scope, $http) {

      window.s = $scope;
      
      $scope.parent_data = {{ json_encode($parent_data) }}
        $scope.child_data=child_data
       $scope.child =  $scope.child_data.name
       $scope.dmt_child_data=dmt_child_data
      $scope.creditRequest = {
        child : $scope.child_data.name,
        child_id : $scope.dmt_child_data.id
      }
      
      $scope.submit = submit
      $scope.invalidAmount = invalidAmount
       
      function submit (creditRequest) {
       
        if ($scope.creditRequestFrm.$invalid || invalidAmount(creditRequest.amount)) return
       // if ($scope.parent_data.balance < creditRequest.amount) return sweetAlert('Error', 'Insufficient Balance.', 'error')

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
    console.log(creditRequest)
    if (isConfirm) {
      req = Object.assign(creditRequest, {user_id: {{Cookie::get('userid')}}})

        $http.post(`/api/v1/users/${req.user_id}/actions/credit-request`, req)
        .then(data => {
          if(data.data.status == 1)
          {
 oldToastr.success("Credit sent to the agent.")
           sweetAlert('Success', 'Credit sent to the agent.', 'success')
           location.reload();
          }else
          {
             oldToastr.success(data.data.description)
           sweetAlert('error', data.data.description, 'error')
           //location.reload();
          }
          // oldToastr.success("Credit sent to the agent.")
          //  sweetAlert('Success', 'Credit sent to the agent.', 'success')
           //location.href = $scope.dmt_child_data.type == 1 ? "/agents" : $scope.dmt_child_data.type == 2 ? "/distributors" : '/'
        }, function (err) {
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Missing data. Fill all the details please.', 'error')
            return
          }
          if (err.data.code && err.data.code == 2) {
            sweetAlert('Error', 'Insufficient Balance.', 'error')
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
        console.log(amount);
        return amount >= 100 && amount <= 2500000 ? false : true
      }

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
