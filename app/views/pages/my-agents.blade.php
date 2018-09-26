<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AgentsCtrl" class="head-weight">
<div class="row">
  <div class="col-md-1" style="padding-top:5px;">Search</div>
  <div class="col-md-3">
      <input class="form-control input-sm ng-pristine ng-valid ng-touched" type="text" ng-model="search">
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">My Agents<span class="panel-subtitle">List</span></div>
      <div class="panel-body">
        <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="row">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>NAME</th>
                    <th>CSR ID</th>
                    <th>EMAIL</th>
                    <th>MOBILE</th>
                    <th>JOINING DATE</th>
                    <th>BALANCE</th>
                    <th>ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr  ng-hide="record<1" ng-repeat="agent in requestList |  filter:search">
                    <td>@{{agent.user_name}}</td>
                    <td>@{{agent.bank_user_id}}</td>
                    <td>@{{agent.email}}</td>
                    <td>@{{agent.mobilenumber}}</td>
                    <td>@{{agent.created_on | date:'MMM dd, yyyy'}}</td>
                    <td>@{{agent.wallet_balance | currency: 'Rs. '}}</td>
                    <td>
                      <button type="button" ng-click="credit(agent)" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button>
                      <!-- <button type="button" ng-click="debit(agent)" class="btn btn-danger btn-xs" style="margin-left: 5%;"><i class="fa fa-minus"></i></button> -->
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--code for pagination-->
                  <!--a href="" type="button" class="btn btn-info btn-sm">Next</a-->
                    <ul class="pagination"  ng-hide="pagination_buttons.length<1">
                      <li ng-show="current_page>1">
                          <a href="/agents?page=1">First</a>
                      </li>
                      <li ng-repeat="i in pagination_buttons" ng-class="{true: 'active', false: ''}[current_page == i]">
                        <a href="/agents?page=@{{ i}}">@{{ i}}</a>
                      </li>
                      <li  class="disabled" ng-show="button_number>button_to_show && (last_page-current_page)>=button_to_show">
                        <a href="">.......</a>
                      </li>
                      <li ng-show="button_number>=10 && (last_page-current_page)>9">
                        <a href="/agents?page=@{{ last_page}}">Last</a>
                      </li>

                                      <!--li class="active"><a href="#">@{{ pagination_buttons}}</a></li-->

                      </ul>
                </div>
              <!-- end -->
              <h4 ng-show="agents.length == 0">No agents added yet</h4>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    
   var requestList= {{ json_encode($requestList) }}

console.log(requestList);
  </script>
</div>
@stop
@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('AgentsCtrl', ['$scope', '$http', function ($scope, $http) {
      window.s = $scope
      $scope.credit = credit
      $scope.debit = debit
      $scope.requestList = requestList

      
    /* code for pagination*/
    var per_page=100;
    var button_to_show=10;
     var button_number;
       var total_button={{$total }};

       button_number={{$total }};
       var current_page={{$current_page }};;
       var last_page=Math.ceil({{$total }}/per_page);

       if(button_number>button_to_show){
        if((last_page-current_page)>=button_to_show){
            button_number=current_page+button_to_show;
        }else{
            button_number=last_page;
        }
    }
      $scope.button_to_show=button_to_show

      $scope.button_number=button_number
        $scope.current_page=current_page
        $scope.per_page=per_page
        $scope.last_page=last_page
        var pagination_buttons = []
        current_page=current_page>9?(current_page-5):current_page
        for(var i=current_page;i<=button_number;i++) {
            pagination_buttons.push(i)
        }
        $scope.pagination_buttons=pagination_buttons

    /*end */
$scope.updatedBalance = getUpdatedBalance();
        
       


            function getUpdatedBalance () {
                    $http.post('/api/v1/getupdatedbalance', {'user_id': {{Cookie::get('userid')}}})
                        .then(function (data) {
                            
                           // console.log(data.data.wallet_balance)
                            $scope.wallectBalance=data.data.wallet_balance
                            
                        }, failed)
                }

      function credit (agent) {
        if($scope.wallectBalance == 0)
          return sweetAlert('Error', 'Insufficient Balance.', 'error')
        location.href = `/users/actions/csddfflflf-request/vendor/${btoa(agent.userid)}-${agent.user_name}`
      }
      function debit (agent) {
        location.href = `/users/actions/debit-request/vendor/${btoa(agent.userid)}-${agent.user_name}`
      }
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
      function failed (err) {
          sweetAlert('Error', 'ERROR', 'error')
        }
    }])
</script>
@stop