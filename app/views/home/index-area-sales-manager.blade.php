<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider"><h3><strong>Today's Overview</strong></h3></div>
					<?php /** <div class="panel-body dashboard-views">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4><strong>Deposit</strong></h4>
									</div>
								</div>
								<div class="seperator">
									<div class="row">
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Count</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_today}}</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Amount</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_today || 0 }}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4><strong>Withdraw</strong></h4>
									</div>
								</div>
								<div class="seperator">
									<div class="row">
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Count</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_today }}</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Amount</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<h4><strong>Balance Enquiry</strong></h4>
											</div>
										</div>
										<div class="seperator">
											<div class="row">
												<div class="col-md-12">
													<div class="widget widget-tile">
														<!-- <div id="spark1" class="chart sparkline"></div> -->
														<div class="data-info">
															<div class="desc">Count</div>
															<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_today }}</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<h4><strong>Commission</strong></h4>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="widget widget-tile">
													<!-- <div id="spark1" class="chart sparkline"></div> -->
													<div class="data-info">
														<div class="desc">Amount</div>
														<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{commission_today || 0}}</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> **/ ?>
			</div>
		</div>
	</div>
	<?php /** 
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-divider"><h3><strong>Weekly Overview</strong></h3></div>
					<div class="panel-body dashboard-views">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4><strong>Deposit</strong></h4>
									</div>
								</div>
								<div class="seperator">
									<div class="row">
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Count</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_weekly}}</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Amount</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_weekly || 0}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4><strong>Withdraw</strong></h4>
									</div>
								</div>
								<div class="seperator">
									<div class="row">
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Count</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_weekly}}</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Amount</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_weekly || 0 }}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<h4><strong>Balance Enquiry</strong></h4>
											</div>
										</div>
										<div class="seperator">
											<div class="row">
												<div class="col-md-12">
													<div class="widget widget-tile">
														<!-- <div id="spark1" class="chart sparkline"></div> -->
														<div class="data-info">
															<div class="desc">Count</div>
															<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_weekly}}</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<h4><strong>Commission</strong></h4>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="widget widget-tile">
													<!-- <div id="spark1" class="chart sparkline"></div> -->
													<div class="data-info">
														<div class="desc">Amount</div>
														<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ commission_weekly || 0 }}</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-divider"><h3><strong>Monthly Overview</strong></h3></div>
					<div class="panel-body dashboard-views">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4><strong>Deposit</strong></h4>
									</div>
								</div>
								<div class="seperator">
									<div class="row">
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Count</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_monthly}}</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Amount</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_monthly || 0}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4><strong>Withdraw</strong></h4>
									</div>
								</div>
								<div class="seperator">
									<div class="row">
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Count</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_monthly}}</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="widget widget-tile">
												<!-- <div id="spark1" class="chart sparkline"></div> -->
												<div class="data-info">
													<div class="desc">Amount</div>
													<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_monthly || 0}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<h4><strong>Balance Enquiry</strong></h4>
											</div>
										</div>
										<div class="seperator">
											<div class="row">
												<div class="col-md-12">
													<div class="widget widget-tile">
														<!-- <div id="spark1" class="chart sparkline"></div> -->
														<div class="data-info">
															<div class="desc">Count</div>
															<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_monthly}}</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<h4><strong>Commission</strong></h4>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="widget widget-tile">
													<!-- <div id="spark1" class="chart sparkline"></div> -->
													<div class="data-info">
														<div class="desc">Amount</div>
														<div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i>@{{ commission_monthly || 0 }}</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div> **/ ?>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
window.s = $scope;




}])
</script>
@stop
