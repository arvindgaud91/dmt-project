<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">Wallet Reports<span class="panel-subtitle">Snapshot</span></div>
				<div class="panel-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="panel panel-default panel-table">
								<div class="panel-heading">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
									<div class="title">Admin Transaction</div>
									<br>
									
								</div>
								<div class="panel-body table-responsive">
									<!-- <table ng-show="transactions.length > 0" class="table table-striped table-borderless"> -->
									<table class="table table-striped table-borderless">
										<thead>
											<tr>
												<th>Ticket ID</th>
												<th>Type</th>
												<th>Message</th>
												
												<th>Status</th>
												<th>Response</th>
												<th>Ticket Date</th>
												<th>Response Date</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
                                                                                    @foreach($support_data as $support)
											<tr >
												<td>{{$support->ticket_id}}</td>
												<td>{{$support->type}}</td>
												<td>{{$support->message}}</td>
												<td>{{$support->status}}</td>
												<td>{{$support->response}}</td>
												<td>{{$support->created_at}}</td>
												<td>{{$support->response_date}}</td>
												
											</tr>
                                                                                        @endforeach
										</tbody>
									</table>
									{{$support_data->links();}}
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="col-xs-12 col-md-12">
						<div class="widget widget-calendar">
							<div id="calendar-widget"></div>
						</div>
					</div> -->
					<!-- End Row -->
				</div>
			</div>
		</div>
	</div>
</div>
@stop
