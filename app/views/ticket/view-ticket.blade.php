<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<style>
table {
    border-collapse: collapse;
    /*width: 100%;*/
}

th, td {
    text-align: left;
    padding: 5px;
}

/*tr:nth-child(even){background-color: #f2f2f2}*/

th {
    background-color: #4CAF50;
    color: white;
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
  <div ng-controller="ViewTicketCtrl" class="head-weight">
           <div class="row">
              <div class="col-md-12">
                  <div class="hpanel ">
                      <div class="panel-heading hbuilt">
                          Ticket Details
                            <button class="btn btn-primary" style="float: right;"><a href="/all-ticket" style="color: white !important;">Back</a></button>
                      </div>
                      <div class="panel-body no-padding">
                        <div class="row">
                            <div class="col-md-6">
                              <table>
                                <tr>
                                  <td>Ticket No.</td>
                                  <td>:</td>
                                  <td>@{{ticket_no}}</td>
                                </tr>
                                <tr>
                                  <td>Created At</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.created_date | dateToISO | date:'medium'}}</td>
                                </tr>
                                <tr>
                                  <td>Priority</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.ticket_priority | capitalize}}</td>
                                </tr>
                                <tr>
                                  <td>Product</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.ticket_product}}</td>
                                </tr>
                                <tr>
                                  <td>Status</td>
                                  <td>:</td>
                                  <!-- <td>@{{ticket_data.current_status}}</td> -->
                                  <td ng-if="ticket_data.current_status == 'Open'"><span class="p-open">@{{ticket_data.current_status}}</span></td>
                                  <td ng-if="ticket_data.current_status == 'Closed'"><span class="p-closed">@{{ticket_data.current_status}}</span></td>
                                  <td ng-if="ticket_data.current_status == 'Suspended'"><span class="p-other">@{{ticket_data.current_status}}</span></td>
                                </tr>
                                <tr>
                                  <td>Created By</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.created_by}}</td>
                                </tr>
                                <tr>
                                  <td>Issue 1</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.issue1}}</td>
                                </tr>
                                <tr>
                                  <td>Issue 2</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.issue2}}</td>
                                </tr>
                                <tr>
                                  <td>Issue Description</td>
                                  <td>:</td>
                                  <td>@{{ticket_data.ticket_comment}}</td>
                                </tr>
                              </table>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="hpanel ">
                      <div class="panel-heading hbuilt">
                          Comments
                      </div>
                      <div class="panel-body no-padding" ng-show="!ticket_data.count_of_comment>0">
                        <div class="row">
                          <div class="col-md-12 ">
                            <div class="chat-discussion" style="height: auto !important;">
                              <div class="message">
                                  <span class="message-content">
                                    <h5>No comments added yet.</h5>
                                  </span>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="panel-body no-padding" ng-show="ticket_data.count_of_comment>0">
                          <div class="row">
                              <div class="col-md-12 ">
                                  <!-- <div class="chat-discussion">
                                    <div ng-repeat="comment in ticket_data.CommentList">
                                      <div ng-if="$even" class="chat-message left">
                                          <div class="message">
                                              <a class="message-author"> @{{comment.created_by}} </a>
                                              <span class="message-date">  @{{comment.created_date | dateToISO | date:'medium'}} </span>
                                                  <span class="message-content">
                            @{{comment.comment}}
                                                  </span>
                                          </div>
                                      </div>
                                      <div ng-if="$odd" class="chat-message right">
                                          <div class="message">
                                              <a class="message-author" href="#"> @{{comment.created_by}} </a>
                                              <span class="message-date">  @{{comment.created_date | dateToISO | date:'medium'}} </span>
                                                  <span class="message-content">
                            @{{comment.comment}}
                                                  </span>
                                          </div>
                                      </div>
                                    </div>
                                  </div> -->
                                  <div class="chat-discussion">
                                    <div ng-repeat="comment in ticket_data.CommentList">
                                      <div ng-if="comment.created_by_type == 'Customer'" class="chat-message left">
                                          <div class="message">
                                              <a class="message-author"> @{{comment.created_by}} </a>
                                              <span class="message-date">  @{{comment.created_date | dateToISO | date:'medium'}} </span>
                                                  <span class="message-content">
                            @{{comment.comment}}
                                                  </span>
                                          </div>
                                      </div>
                                      <div ng-if="comment.created_by_type != 'Customer'" class="chat-message right">
                                          <div class="message">
                                              <a class="message-author" href="#"> @{{comment.created_by}} </a>
                                              <span class="message-date">  @{{comment.created_date | dateToISO | date:'medium'}} </span>
                                                  <span class="message-content">
                            @{{comment.comment}}
                                                  </span>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- <div class="panel-footer borders" ng-if="ticket_data.current_status !== 'Closed'"> -->
                      <div class="panel-footer borders">
                        <form name="addCommentFrm" ng-submit="submit(addComment)" novalidate>
                          <div class="input-group">
                              <!-- <input type="text" class="form-control" ng-model="addComment.comment" name="comment" placeholder="Type your comment here..." required>
                              <p ng-show="addCommentFrm.$submitted && addCommentFrm.comment.$invalid" class="err-mark">Please enter comment.</p> -->
                              <textarea class="form-control" ng-maxlength="1000" ng-minlength="10" ng-model="addComment.comment" name="comment" placeholder="Type your comment here..." required></textarea>
                              <p ng-show="addCommentFrm.$submitted && addCommentFrm.comment.$error.required" class="err-mark">Please enter issue description.</p>
                              <p ng-show="addCommentFrm.comment.$error.maxlength" class="err-mark">Entered comment is too long.It need to be 10 to 1000 characters.</p>
                              <p ng-show="addCommentFrm.comment.$error.minlength" class="err-mark">Entered comment is too short.It need to be 10 to 1000 characters.</p>
                              <span class="input-group-btn">
                                  <button type="submit" class="btn btn-success">
                                      Send</button>
                              </span>
                          </div>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('ViewTicketCtrl', ['$scope', '$http', function ($scope, $http) {

      $scope.submit = submit;

      //window.s = $scope;
      $scope.ticket_data = {{ $ticket_data }};
      $scope.ticket_no = "{{ $ticket_no }}";
      console.log($scope.ticket_data);
      // console.log($scope.comments.length);

      function submit (addComment) {    
        if ($scope.addCommentFrm.$invalid) return

        req = Object.assign(addComment,{ticket_no: $scope.ticket_no})

        $http.post(`/api/v1/ticket/insert-comment`, req)
        .then(data => {
          // console.log(data);
          // oldToastr.success("Comment has been added successfully.")
          sweetAlert('Success', data.data, 'success')
          window.location.reload();
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
