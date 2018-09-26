<?php use Acme\Auth\Auth; 
$user = Auth::user();?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider"><h4>Transaction Reports</h4><!--<span class="panel-subtitle">Snapshot</span>--></div>
				<div class="panel-body">
                   <!--  <div class="row">
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    NEFT Transactions
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="neft-transactions"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    IMPS Transactions
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="imps-transactions"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    Transactions
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="transactions-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                     <!-- <div>
                      
				{{Form::open(array("url"=>"/export-transactions-report"))}}
                   <div class='col-md-5'>
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker6'>
                                <input type='text' class="form-control" placeholder="From Date" name="from_date" required=""/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-5'>
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker7'>
                                <input type='text' class="form-control" placeholder="To Date" name="to_date" required=""/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-2'>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Export</button>
                        </div>
                    </div>
                {{Form::close()}}
            </div>
                    -->     <!--     <div class="col-md-3">
                             <select ng-model="searchObj.type" class="form-control">
                                    <option value="">Select an option</option>
                                    <option value="bankTransactionId">Bank Transaction ID</option>
                                    <option value="senderName">Agent Name</option>
                                    <option value="phoneNo">Phone Number</option>
                                    </select>
                            </div>
                          <div class="col-md-3">
                            <input ng-model="searchObj.queryString" placeholder="Search term" type="text" class="form-control">
                          </div>
                          <div class="col-md-2" style="margin-bottom: 10px;">
                            <button ng-click="searchTransactions(searchObj)" class="btn btn-primary">Search</button>
                          </div> -->
                            <div class="col-md-12">
                          	
                          
                               {{Form::open(array("url"=>"/transaction-reportsdatewiseexport"))}}
              <div class='col-md-4'>
                <div class="form-group">
                  <div class='input-group date' id='datetimepicker6'>
                    <input type='text' class="form-control" placeholder="FromDate" name="from_date" required=""/>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
                </div>
              </div>
              <div class='col-md-4'>
                <div class="form-group">
                  <div class='input-group date' id='datetimepicker7'>
                    <input type='text' class="form-control" placeholder="ToDate" name="to_date" required=""/>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
                </div>
              </div>
              <div class='col-md-2'>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Export</button>
                </div>
              </div>
          {{Form::close()}}

          </div>




                          <div class="col-md-12">
                          	
                          
                               {{Form::open(array("url"=>"/transaction-reportsdatewise"))}}
              <div class='col-md-4'>
                <div class="form-group">
                  <div class='input-group date' id='datetimepicker8'>
                    <input type='text' class="form-control" placeholder="FromDate" name="from_date" required=""/>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
                </div>
              </div>
              <div class='col-md-4'>
                <div class="form-group">
                  <div class='input-group date' id='datetimepicker9'>
                    <input type='text' class="form-control" placeholder="ToDate" name="to_date" required=""/>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
                </div>
              </div>
              <div class='col-md-2'>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">view</button>
                </div>
              </div>
          {{Form::close()}}

          </div>



						<div class="col-md-12">
							<div class="panel-default panel-table">
								<!-- <div class="panel-heading">
									 <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> 
									 <div class="title">Last Five Transactions</div>
								</div> -->
       

								@if (Session::has('error'))
                               <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif

                                @if (Session::has('success'))
                               <div class="alert alert-success">{{ Session::get('success') }}</div>
                                @endif
								<div class="panel-heading">
										<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
										<!--<div class="title">Search <input class="input-sm" type="text" ng-model="search"></div>
										<br>-->
                                        <div class="row">
                                            <div class="col-md-1" style="padding-top:5px;">Search</div>
                                            <div class="col-md-3">
                                                <input class="form-control input-sm" type="text" ng-model="search">
                                            </div>
                                        </div>
							 	</div>
							 	<div class="table-responsive">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Bank Transaction Id</th>
												<th>Bank Remarks</th>
												<th>Bank Reference No</th>
												<th>Transaction Date</th>
												<th>Sender Name</th>
												<th>Sender Phone No</th>
												<th>Beneficiary Id</th>
                        <th>Beneficiary Name</th>
												<th>Beneficiary AccountNo</th>

												<th>Total Amount</th>
												
												
												
												<th>Receipt</th>
												<th>Requery</th>
												
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr  ng-hide="record<1" ng-repeat="transaction in transactions |  filter:search">
												<td>@{{transaction.bank_tran_id}}</td>
                                               <!--  <td ng-if="transaction.remarks=='Insufficient Avail Bal  Excp'">FAILUREIssuing bank CBS or node offline</td>
                                                <td ng-if='transaction.remarks!="Insufficient Avail Bal  Excp"'>@{{transactions.remarks}}</td> -->
                                                 <td ng-if="transaction.refund_status == 1" class="ng-scope">
                                                    <span class="label label-warning ng-binding">
                                                        Refunded
                                                    </span>
                                                </td>
												<td ng-if="(transaction.remarks).includes('Insufficient Avail Bal')" class="ng-scope">
                                                        BANK NODE OFFLINE
                                                </td>
                        <td ng-if="transaction.refund_status != 1 && !(transaction.remarks).includes('Insufficient Avail Bal')" >@{{transaction.remarks}}</td>
												<td>@{{transaction.channelpartnerrefno}}</td>
												<td>@{{transaction.transaction_on | date: 'medium'}}</td>
												<td>@{{transaction.remittername}}</td>
												<td>@{{transaction.remittermobilenumber}}</td>
												<td>@{{transaction.beneficiaryid}}</td>
											<td>@{{transaction.beneficiaryname}}</td>
                      <td>@{{transaction.accountnumber}}</td>
												<td>@{{transaction.tran_amount | currency: 'Rs. '}}</td>
                                                
												
												
												
												<td><!--<a class="btn btn-primary" type="submit" ng-href="/receipts/@{{transaction.transaction_group_id}}">Receipt</a>--><a class="btn btn-xs btn-success" type="submit" ng-href="/receipts/@{{transaction.tran_group_id}}"><i class="fa fa-file-text-o"></i> Receipt</a></td>
												<td><!--<a class="btn btn-primary" type="submit" ng-href="/requery/@{{transaction.bank_transaction_id}}">Update</a>--><a class="btn btn-xs btn-info" type="submit" ng-href="/requery/@{{transaction.channelpartnerrefno}}"><i class="fa fa-edit"></i> Update</a></td>
												
                                                    
												
												
													
											</tr>
                                            <tr>
                                                <td ng-hide="record > 0" colspan="14">No transactions conducted yet.</td>
                                            </tr>
										</tbody>
									</table>

									<!--code for pagination-->
									<!--a href="" type="button" class="btn btn-info btn-sm">Next</a-->
					          <ul class="pagination"  ng-hide="pagination_buttons.length<1">
					            <li ng-show="current_page>1">
					                <a href="/transaction-reportsdatewise/1/{{$from_date}}/{{$to_date}}">First</a>
					            </li>
					            <li ng-repeat="i in pagination_buttons" ng-class="{true: 'active', false: ''}[current_page == i]">
					              <a href="/transaction-reportsdatewise/@{{ i}}/{{$from_date}}/{{$to_date}}">@{{ i}}</a>
					            </li>
					            <li  class="disabled" ng-show="button_number>button_to_show && (last_page-current_page)>=button_to_show">
					              <a href="">.......</a>
					            </li>
					            <li ng-show="button_number>=10 && (last_page-current_page)>9">
					              <a href="/transaction-reports?page=@{{ last_page}}">Last</a>
					            </li>

					                            <!--li class="active"><a href="#">@{{ pagination_buttons}}</a></li-->

					            </ul>
								</div>
							<!-- end -->
									
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
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>>
<script>
$('.input-daterange input').each(function(){
	$(this).datepicker('clearDates');
});
	var transactions = {{json_encode($transactions) }}
	var record = {{$record }}


</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	window.s = $scope
/* code for pagination*/
var per_page=100;
var button_to_show=10;
 var button_number;
   var total_button={{$total }};
   button_number={{$total }};

   var from_date={{$from_date }};
   var to_date ={{$to_date }};
   $scope.from_date=from_date
   $scope.to_date=to_date
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


	$scope.transactions = transactions.map(formatTransaction)
	           
    $scope.searchTransactions = searchTransactions
	$scope.record = record
    function searchTransactions (obj) {

        if (! obj || ! obj.type || ! obj.queryString) {
          alert('Please select a type and fill the search term')
          return false
        }
        window.location.href = window.location.pathname + '?type=' + obj.type + '&queryString=' + obj.queryString
    }                        
	function formatTransaction (tx){
		tx.created_at = new Date(tx.created_at)
		return tx
	}

	function fail (err) {
		sweetAlert('Error', 'Something went wrong', 'error')
	}
    
  
}])
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<script type="text/javascript">
	    $(function () {
	        $('#datetimepicker6').datetimepicker({ format: 'YYYY-MM-DD',maxDate:new Date()});

$('#datetimepicker8').datetimepicker({ format: 'YYYY-MM-DD',maxDate:new Date()});
$('#datetimepicker9').datetimepicker({ format: 'YYYY-MM-DD',maxDate:new Date()});

	        $('#datetimepicker7').datetimepicker({
	            format: 'YYYY-MM-DD',
	            
	            useCurrent: false 
	        });
	        $("#datetimepicker6").on("dp.change", function (e) { 
	          
	            $('#datetimepicker7').data("DateTimePicker");
	        });
	        $("#datetimepicker7").on("dp.change", function (e) {
	          
	            var from_date = moment($("input[name=from_date]").val(),"YYYY-MM-DD");
	            var to_date = moment($("input[name=to_date]").val(),"YYYY-MM-DD"); 
	          
	            var days_difference = to_date.diff(from_date, 'days');
	            if(days_difference >30)
	            {
	           
	                    sweetAlert('Error', 'Date diffrence should be eqaual to 30 or less than 30', 'error');
	            
	            $("input[name=to_date]").val("");
	            }
	        
	        });
	    });
	</script>
@stop