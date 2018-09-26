<?php use Acme\Auth\Auth; 
$user = Auth::user();
?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight dashboard">
	<div class="row">
        <div class="col-lg-12 text-center welcome-message">
            <?php $domain_data = preg_replace('#^https?://#', '', Request::root()); ?>
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
                <p style="color: blue">
                      <strong>Dear Valued Partner, 
Please note that as per government regulations, you are required to provide your GSTIN number. Kindly share it by 20th June 2018.</strong>  
                </p>
            </marquee>
            
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
                                <i class="fa fa-laptop"></i> Active users in current month ({{ @date('Y') }})
                            </div>
                          
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
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-qrcode" aria-hidden="true"></i> <strong>Total Agents</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4>@{{child_count}}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					<h3><i class="fa fa-barcode" aria-hidden="true"></i> <strong>Total Balance With Agents</strong></h3>
				</div>
				<div class="panel-body dashboard-views">
					<div class="row">
						<div class="col-md-12">
							<h4><i class="fa fa-inr" aria-hidden="true"></i>@{{ child_balance | currency: ''}}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
window.s = $scope;
$scope.registerGST=registerGST;
$scope.child_count = {{ $child_count }}
$scope.child_balance = {{ $child_balance }}

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
}])
</script>
@stop