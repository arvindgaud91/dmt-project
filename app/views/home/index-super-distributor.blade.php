<?php use Acme\Auth\Auth; 

$user = Auth::user();
?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight">
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
            <h2>Welcome to AONEHUB</h2>\
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
            <!-- <p>
                        Special <strong>Admin Theme</strong> for medium and large web applications with very clean and
                        aesthetic style and feel.
                    </p> -->
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
                        <div class="col-md-6">
                            <div class="text-center small">
                                <i class="fa fa-laptop"></i> Active users in current month ({{ @date('Y') }})
                            </div>
                            <div class="flot-chart" style="height: 160px">
                                <div class="flot-chart-content" id="flot-line-chart"></div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                                <div class="small">
                                    <i class="fa fa-clock-o"></i> Active duration
                                </div>
                                <?php
                                $then = $user->created_at;
                                $then = new DateTime($then);
                                 
                                $now = new DateTime();
                                 
                                $sinceThen = $then->diff($now);
                                 
                                ?>
                                <div>
                                    <h1 class="font-extra-bold m-t-xl m-b-xs">
                                        <?php echo $sinceThen->m; ?>Months
                                    </h1>
                                    <small>And <?php echo $sinceThen->d; ?> days</small>
                                </div>
                                <!-- <div class="small m-t-xl">
                                    <i class="fa fa-clock-o"></i> Last active in 12.10.2015
                                </div> -->
                                
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
    <div class="row animate-panel">
        <div class="panel-heading">Super Distributor Dashboard</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Agents</h4>
                        </div>
                        <div class="stats-icon pull-right">
                           <i class="pe-7s-check fa-4x c-purple"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">0</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Distributors</h4>
                            </div>
                            <div class="stats-icon pull-right">
                               <i class="pe-7s-check fa-4x c-purple"></i>
                            </div>
                            <div class="clearfix"></div>
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-income-chart"></div>
                            </div>
                            <div class="m-t-xs">

                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">0</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Balance</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">0</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
    </div>
  <!--<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider"><h3><strong>Super Distributor Dashboard</strong></h3></div>
        <div class="panel-body dashboard-views">
          <div class="row">
            <div class="col-md-4">
              <h4><strong>Total Agents</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
             <div class="col-md-4">
              <h4><strong>Total Distributors</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong>Total Balance</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>-->
    <div class="row">
        <div class="panel-heading">Today's Overview</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Number Of Transactions</h4>
                        </div>
                        <div class="stats-icon pull-right">
                           <i class="pe-7s-check fa-4x c-purple"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">0</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Amount</h4>
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
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">0</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <?php
            $domain_data = preg_replace('#^https?://#', '', Request::root());
        ?>
        @if(!($domain_data == 'am-tech.digitalindiapayments.com'))
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission Earned</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">0</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        @endif
    </div>
  <!--<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider">
          <h3><strong>Today's Overview</strong></h3>
        </div>
        <div class="panel-body dashboard-views">
          <div class="row">
            <div class="col-md-4">
              <h4><strong>Total Number Of Transactions</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong>Total Amount</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong> Commission Earned</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>-->
    <div class="row">
        <div class="panel-heading">Weekly Overview</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Number Of Transactions</h4>
                        </div>
                        <div class="stats-icon pull-right">
                           <i class="pe-7s-check fa-4x c-purple"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">0</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Amount</h4>
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
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">0</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <?php
            $domain_data = preg_replace('#^https?://#', '', Request::root());
        ?>
        @if(!($domain_data == 'am-tech.digitalindiapayments.com'))
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission Earned</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">0</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        @endif
    </div>
  <!--<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider"><h3><strong>Weekly Overview</strong></h3></div>
        <div class="panel-body dashboard-views">
          <div class="row">
            <div class="col-md-4">
              <h4><strong>Total Number Of Transactions</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong>Total Amount</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong> Commission Earned</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>-->
  <!--<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider">
          <h3><strong>Monthly Overview</strong></h3>
        </div>
        <div class="panel-body dashboard-views">
          <div class="row">
            <div class="col-md-4">
              <h4><strong>Total Number Of Transactions</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong>Total Amount</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4><strong> Commission Earned</strong></h4>
              <div class="widget widget-tile">
                <div class="data-info">
                  <div class="value">
                    <span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" class="number">0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>-->
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