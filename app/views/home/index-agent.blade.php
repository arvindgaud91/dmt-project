<?php 
use Acme\Auth\Auth; 

$user = Auth::user();
?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight dashboard">
    <div class="row">
        <div class="col-lg-12 text-center welcome-message">
            <?php $domain_data = preg_replace('#^https?://#', '', Request::root());  ?>
            @if($domain_data == 'dmt.mysuravi.com:8021')
            <h2>Welcome to SURAVI</h2>
            @elseif($domain_data == 'dmt.lrprch.in:8021')
            <h2>Welcome to LRP MULTI RECHARGE</h2>
            @elseif($domain_data == 'dmt.manjilgroup.com:8021')
            <h2>Welcome to MANJIL GROUP</h2>
            @elseif($domain_data == 'dmt.indiapaysolution.com:8021')
            <h2>Welcome to INDIA PAY SOLUTION</h2>
            @elseif($domain_data == 'dmt.samriddhifoundation.net.in:8021')
            <h2>Welcome to SAMRIDDHI FOUNDATION</h2>
            @elseif($domain_data == 'dmtservices.primagemarketing.com:8021')
            <h2>Welcome to PRIMAGE VINCOME PVT. LTD</h2>
            @elseif($domain_data == 'dmt.amenitiesservices.in:8021')
            <h2>Welcome to AMENITIES SERVICES</h2>
            @elseif($domain_data == 'dmt.ekiosk.in:8021')
            <h2>Welcome to eKiosk</h2>
            @elseif($domain_data == 'dmt.aonehub.com:8021')
            <h2>Welcome to AONEHUB</h2>
            @elseif($domain_data == 'dmt.zippays.in:8021')
            <h2>Welcome to ZIPPAY</h2>
            @elseif($domain_data == 'dmtpearltek.digitalindiapayments.com:8021')
            <h2>Welcome to PEARL TEK</h2>
            @elseif($domain_data == 'dmt.akpayments.in:8021')
            <h2>Welcome to A.K ENTERPRISES</h2>
            @elseif($domain_data == 'dmt.oneindiapayments.com:8021')
            <h2>Welcome to ONE INDIA PAYMENT</h2>
            @else
            <h2>
                Welcome to Digital India Payments Ltd
            </h2>
            @endif
           <marquee width="100%">
                <p style="color: Red">
                      <strong>Dear Users, Digital India Payments Limited. Please note call center network is under maintainence. For any query kindly generate ticket through support option.strong>  
                </p>
            </marquee>

            <!-- <p style="color: Red">
                      <strong>Dmt will remain down from 20:00 to 10:00 am tomorrow for technical maintenance.</strong>  
                </p>
 -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel animate-panel">
                <div class="panel-heading">
                    <!-- <div class="panel-tools">
                                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                                <a class="closebox"><i class="fa fa-times"></i></a>
                            </div> -->
                    Dashboard information and statistics
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="small">
                                <i class="fa fa-bolt"></i> Today's Date
                            </div>
                            <div>
                                <h2 class="font-extra-bold m-t-xl m-b-xs">
                                        <?php echo date("dS M Y");?>
                                </h2>
                               <!--  <small>Company Agent Views</small> -->
                            </div>
                            <div class="small m-t-xl">
                                <i class="fa fa-clock-o"></i> Data from {{ @date('Y') }}
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="text-center small">
                                <i class="fa fa-laptop"></i> Add GST Number Form({{ @date('Y') }})

                              <!--   RSS ***************************************************** -->

                                <!-- <i class="fa fa-laptop"></i> GST Number -->

                                <form class="form-signin ng-pristine ng-invalid ng-invalid-required ng-valid-isfloat" name="gstRequestobjFrm" ng-submit="registerGST(gstRequest)" novalidate="" style="padding-left: 233px;">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="gst"></label>
                                                    <input type="text" ng-model="gstRequest.gst" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required"  name="gst" placeholder="GST Number" required="">
                                                    <p ng-show="gstRequestobjFrm.$submitted &amp;&amp; gstRequestobjFrm.gst.$invalid" class="err-mark ng-hide">Please enter GST number.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success" ng-disabled="isDisabled" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT GST</small></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                             
                                
                            </div>
                    </div>
                </div>
                <!--<div class="panel-footer">
                        <span class="pull-right">
                              You have two new messages from <a href="">Monica Bolt</a>
                        </span>
                            Last update: 21.05.2015
                        </div>-->
            </div>
        </div>
    </div>
    <div class="animate-panel">
	<div class="row">
        <div class="col-lg-4">
            <div class="hpanel stats">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4><i class="fa fa-qrcode" aria-hidden="true"></i> Transactions for {{@date('F')}} {{ @date('Y') }}</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-download fa-4x c-yellow"></i>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-income-chart"></div>
                    </div>
                    <div class="m-t-xs">

                        <div class="row">
                            <div class="col-xs-5">
                                <h4><i class="fa fa-inr" aria-hidden="true"></i>&nbsp@{{ transactions_monthly | currency: ''}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                   Current Month Transactions
                </div>
            </div>
        </div>
		<!--<div class="col-md-4">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-qrcode" aria-hidden="true"></i> <strong>Transactions for {{@date('F')}} {{ @date('Y') }}</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4><i class="fa fa-inr" aria-hidden="true"></i>&nbsp@{{ transactions_monthly | currency: ''}}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>-->
        <div class="col-lg-4">
            <div class="hpanel stats">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Senders for {{@date('F')}} {{ @date('Y') }}</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-users fa-4x c-blue"></i>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-income-chart"></div>
                    </div>
                    <div class="m-t-xs">
                        <div class="row">
                            <div class="col-xs-5">
                                <h4>@{{ senders_monthly || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    Current Month Senders
                </div>
            </div>
        </div>
		<!--<div class="col-md-4">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-list" aria-hidden="true"></i> <strong>Senders for {{@date('F')}} {{ @date('Y') }}</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4>@{{ senders_monthly || 0 }}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>-->
        <div class="col-lg-4">
            <div class="hpanel stats">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4><i class="fa fa-repeat" aria-hidden="true"></i> Refund for {{@date('F')}} {{ @date('Y') }}</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-cash fa-4x c-green"></i>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-income-chart"></div>
                    </div>
                    <div class="m-t-xs">
                        <div class="row">
                            <div class="col-xs-5">
                                <h4>@{{ monthlyrefund_txn_amt || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                     Current Month Refunds
                </div>
            </div>
        </div>
		<!--<div class="col-md-4">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-repeat" aria-hidden="true"></i> <strong>Refund for {{@date('F')}} {{ @date('Y') }}</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4>0</h4>
						</div>
					</div>
				</div>
			</div>
		</div>-->
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="hpanel stats">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4><i class="fa fa-barcode" aria-hidden="true"></i> Total Transactions</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-download fa-4x c-yellow"></i>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-income-chart"></div>
                    </div>
                    <div class="m-t-xs">
                        <div class="row">
                            <div class="col-xs-5">
                                <h4><i class="fa fa-inr" aria-hidden="true"></i>&nbsp@{{ total_transactions | currency: ''}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    Total Transactions
                </div>
            </div>
        </div>
		<!--<div class="col-md-4">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-barcode" aria-hidden="true"></i> <strong>Total Transactions</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4><i class="fa fa-inr" aria-hidden="true"></i>&nbsp@{{ total_transactions | currency: ''}}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>-->
        <div class="col-lg-4">
            <div class="hpanel stats">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Total Senders</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-users fa-4x c-blue"></i>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-income-chart"></div>
                    </div>
                    <div class="m-t-xs">
                        <div class="row">
                            <div class="col-xs-5">
                                <h4>@{{ total_senders || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    Total Senders
                </div>
            </div>
        </div>
		<!--<div class="col-md-4">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-list" aria-hidden="true"></i> <strong>Total Senders</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4>@{{ total_senders || 0 }}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>-->
        <div class="col-lg-4">
            <div class="hpanel stats">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4><i class="fa fa-refresh" aria-hidden="true"></i> Total Refund</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-cash fa-4x c-green"></i>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-income-chart"></div>
                    </div>
                    <div class="m-t-xs">
                        <div class="row">
                            <div class="col-xs-5">
                               <h4>@{{ overallrefund_txn_amt || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                     Total Refunds
                </div>
            </div>
        </div>
		<!--<div class="col-md-4">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-refresh" aria-hidden="true"></i> <strong>Total Refund</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4>0</h4>
						</div>
					</div>
				</div>
			</div>
		</div>-->
		<div class="clearfix"></div>
	</div>
    </div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
window.s = $scope;
$scope.transactions_monthly = {{ $transactions_monthly }}
$scope.senders_monthly = {{ $senders_monthly }}
$scope.total_transactions = {{ $total_transactions }}
$scope.total_senders = {{ $total_senders }}
$scope.monthlyrefund_txn_amt = {{ $monthlyrefund_txn_amt }}
$scope.overallrefund_txn_amt = {{ $overallrefund_txn_amt }}

$scope.registerGST=registerGST;

// RSS Start ******************//////////////////

function registerGST(gstRequest){
                if ($scope.gstRequest.$invalid) return
                    jQuery(".loader").show(0);
                   
                    var filter1={
                        gst:$scope.gstRequest.gst    
                    }
                
                req = Object.assign(gstRequest, {user_id: {{Cookie::get('userid')}} ,filter1:$scope.gst})

                $http.post('/gstRequest', req).then(data => {
                    console.log(data)
                    if(data.data.status==1)
                    {
                            sweetAlert('Success', 'GST Added Successfully', 'success')
                 setTimeout(function () {
                       location.href = window.location
                    }, 2000)
                    }else
                    {

                   sweetAlert('Error', 'Something Is Wrong.', 'error')

                    }   

                }, fail)
                
            }
            function fail (err) {
             // console.log(err.status)
             
                sweetAlert('Error', 'Something Is Wrong.', 'error')
              
            }
//// RSS End ***********************/////////////////

}])
</script>
@stop
