<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider"><h4>Wallet Reports</h4><!--<span class="panel-subtitle">Snapshot</span>--></div>
				<div class="panel-body">


					<div class="col-md-12">
                          	
                          
                               {{Form::open(array("url"=>"/wallet-reportsdaywiseexport"))}}
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
                          	
                          
                               {{Form::open(array("url"=>"/wallet-reportsdaywise"))}}
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
					<div class="row">
						<div class="col-md-12">
                         <div class="panel-heading">
										<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
										<!--<div class="title">Search <input class="input-sm" type="text" ng-model="search"></div>
										<br>-->
                                        <div class="row">
                                            <div class="col-md-1" style="padding-top:5px;">Search</div>
                                            <div class="col-md-3">
                                                 <input type="text" class="search form-control" placeholder="What you looking for?">
                                            </div>
                                        </div>
							 	</div>
								
<div class="table-responsive">
									<table class="table table-striped table-bordered" id="userTbl">
										<thead>
											<tr>
												 <th>Reference No</th>
												<th>Transaction Date</th>
												
												<th>Activity</th>
												<th>narration</th>
												<th>opening balance</th>
												<th>credit</th>
												
												<th>debit</th>
												<th>closing balance</th>
												
												
												<!-- <th>Status</th>  -->

												
											</tr>
										</thead>
										<tbody class="no-border-x">
											
                                                       
                                               @if($status == 1)
                                               

											@foreach($wallets as $wallet)
											
											<tr>
												
												
												<td>

													 @if(isset($wallet->is_bank_transaction) =='Y')
													  {{$wallet->channelpartnerrefno}}
													 @else
													  {{$wallet->wallet_transaction_id}}


                                                       @endif
                                                         



													</td>
												<td>{{$wallet->wallet_tran_on }}</td>
													
												<td>{{ $wallet->activity }}</td>
												<td>{{$wallet->narration }}</td>
												<td>{{$wallet->opening_balance }}</td>
												<td>
													@if( isset($wallet->credit))
													<span class="label label-success">

														{{ $wallet->credit }}
													RS </span>
													@else
													<span class="label label-success">

														0 
													RS </span>
													@endif

											     </td>
												
												
												<td>
													@if( isset($wallet->debit))
													<span class="label label-danger">

														{{ $wallet->debit }}
													RS </span>
													@else
													<span class="label label-danger">

														0 
													RS </span>
													@endif

											     </td>
												</td>
												<td>{{$wallet->closing_balance }}</td>
												
											
												
												
 
												
												


											</tr>
										
											@endforeach
										@else
										<tr>
											<td colspan="8">No data</td>
										</tr>
										@endif
										</tbody>
									</table>
									</div>
									<!--a href="" type="button" class="btn btn-info btn-sm">Next</a-->
					          <ul class="pagination"  ng-hide="pagination_buttons.length<1">
					            <li ng-show="current_page>1">
					                <a href="/wallet-reports?page=1">First</a>
					            </li>
					            <li ng-repeat="i in pagination_buttons" ng-class="{true: 'active', false: ''}[current_page == i]">
					              <a href="/wallet-reports?page=@{{ i}}">@{{ i}}</a>
					            </li>
					            <li  class="disabled" ng-show="button_number>button_to_show && (last_page-current_page)>=button_to_show">
					              <a href="">.......</a>
					            </li>
					            <li ng-show="button_number>=10 && (last_page-current_page)>9">
					              <a href="/wallet-reports?page=@{{ last_page}}">Last</a>
					            </li>

					                            <!--li class="active"><a href="#">@{{ pagination_buttons}}</a></li-->

					            </ul>
								
							<!-- end -->
									
									
                            </div>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		

@stop
@section('javascript')
<script>
	var wallets = {{ json_encode($wallets) }}
	
</script>
<script>
$(document).ready(function(){
    $('.search').on('keyup',function(){
        var searchTerm = $(this).val().toLowerCase();
        $('#userTbl tbody tr').each(function(){
            var lineStr = $(this).text().toLowerCase();
            if(lineStr.indexOf(searchTerm) === -1){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
    });
});
</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	window.s = $scope
	
	/* code for pagination*/
	var per_page=100;
	var button_to_show=10;
	 var button_number;
	   var total_button={{$total}};

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
	     //current_page=(current_page==last_page)?(current_page-1):current_page
	    current_page=current_page>9?(current_page-5):current_page
	    for(var i=current_page;i<=button_number;i++) {
	        pagination_buttons.push(i)
	   	}
	    $scope.pagination_buttons=pagination_buttons
	    
	/*end */

	function fail (err) {
		console.log(err)
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