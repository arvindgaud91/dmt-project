<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
$user = Auth::user();
$domain_data = preg_replace('#^https?://#', '', Request::root());
?>

<div id="navigation">
    <div class="profile-picture">
        <a href="/users/{{ \Cookie::get('userid') }}/profile">
            <img height="76px" src="/images/profile-pic.jpg" class="img-circle m-b" alt="logo">
        </a>
        <div class="stats-label text-color">
            <span class="font-extra-bold font-uppercase">{{\Cookie::get('user_name')}}</span>
            <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">
                            @if( \Cookie::get('user_type') == 1)
                                Agent
                            @endif
                            @if( \Cookie::get('user_type') == 2)
                                Distributor
                            @endif
                            @if( \Cookie::get('user_type') == 3)
                                Super Distributor
                            @endif
                            @if( \Cookie::get('user_type') == 4)
                                Sales Executive
                            @endif
                            @if( \Cookie::get('user_type') == 5)
                                Area Sales Officer
                            @endif
                            @if( \Cookie::get('user_type') == 6)
                                Area Sales Manager
                            @endif
                            @if( \Cookie::get('user_type') == 7)
                                Cluster Head
                            @endif
                            @if( \Cookie::get('user_type') == 10)
                                State Head
                            @endif
                            @if( \Cookie::get('user_type') == 11)
                                Regional Head
                            @endif
                            <b class="caret"></b>
                        </small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <!-- <li><a href="contacts.html">Contacts</a></li> -->
                        <li><a ng-href="/users/{{ \Cookie::get('userid') }}/profile">Profile</a></li>
                        <!-- <li><a href="analytics.html">Analytics</a></li> -->
                        <li class="divider"></li>
                        <li><a href="/logout">Logout</a></li>
                        <li class="divider"></li>
                      
                    </ul>
                </div>
                <div>
                    <h4 class="font-extra-bold m-b-xs">
                        <a href="#">
                            <!-- <i class="icon mdi mdi-balance-wallet"></i><span>&nbsp;-->
                            <span></i> @{{wallectBalance | currency:'&#8377;'}}</span>
                        </a>
                    </h4>

                </div>
        </div>
    </div>
    <ul class="nav" id="side-menu">
        <li>
            <a href="/"> <span class="nav-label">Dashboard</span></a>
        </li>
        @if(\Cookie::get('user_type') ==2)
        <li >
            <!--<a href="/agents">
                <i class="icon mdi mdi-account"></i><span class="nav-label">My Agents</span>
            </a>-->
            <a href="/agents"><span class="nav-label">My Agents</span><span class="fa fa-users pull-right" ></span></a>
        </li>
        @endif
        @if(\Cookie::get('user_type') ==3)
        <li >
            <!--<a href="/distributors">
                <i class="icon mdi mdi-account"></i><span class="nav-label">My Distributors</span>
            </a>-->
            <a href="/distributors"><span class="nav-label">My Distributors</span><span class="fa fa-users pull-right" ></span></a>
        </li>
        @endif
        @if(\Cookie::get('user_type') ==1)
        <li>
            <!--<a href="/remitter/add">
                <i class="icon mdi mdi-account-add"></i><span class="nav-label">Add Sender</span>
            </a>-->
            <a href="#"><span class="nav-label">Sender</span><span class="fa fa-user-plus pull-right" ></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="/remitter/add">Add</a>
                </li>
                <li>
                    <a href="/remitters">View</a>
                </li>
            </ul>
        </li>
        @endif
        @if(\Cookie::get('user_type') ==1)
<!--
        <li>
            <a href="/remitters">
                <i class="icon mdi mdi-accounts-list"></i><span class="nav-label">Sender List</span>
            </a>
            <a href="/remitters"><span class="nav-label">Sender List</span><span class="fa fa-address-card pull-right" ></span></a>
        </li>
-->
        @endif
        @if(\Cookie::get('user_type')  != 4 && \Cookie::get('user_type')  != 5 && \Cookie::get('user_type')  != 6 && \Cookie::get('user_type')  != 7 && \Cookie::get('user_type')  != 10 && \Cookie::get('user_type')  != 11)
            <li class="">
                <!--<a href="#">
                    <i class="icon mdi mdi-balance-wallet"></i><span class="nav-label">Wallet Request</span>
                </a>-->
                <a href="#"><span class="nav-label">Wallet Request</span><span class="fa fa-download pull-right" ></span></a>
                <ul class="nav nav-second-level">
                    @if(\Cookie::get('user_type') ==1)
                    <li>
                        <a href="/wallets/balance-request/from-distributor">
                            Distributor
                        </a>
                    </li>
                    @endif
                    @if(!($domain_data == 'dmt.mysuravi.com:8021') && !($domain_data =='dmt.lrprch.in:8021') && !($domain_data == 'dmt.manjilgroup.com:8021') && !($domain_data == 'dmt.indiapaysolution.com:8021') && !($domain_data == 'dmt.samriddhifoundation.net.in:8021') && !($domain_data == 'dmtservices.primagemarketing.com:8021') && !($domain_data == 'dmt.amenitiesservices.in:8021') && !($domain_data == 'dmt.ekiosk.in:8021') && !($domain_data == 'dmt.aonehub.com') && !($domain_data == 'dmt.zippays.in:8021') && !($domain_data == 'dmt.akpayments.in:8021') && !($domain_data == 'dmt.oneindiapayments.com:8021'))
                    <li>
                        <a href="/wallets/balance-request">
                            Digital India Payments Limited
                        </a>
                    </li>
                    @endif
                    @if(\Cookie::get('user_type') ==2)
                    <li>
                        <a ng-href="/wallets/balance-request/incoming/vendor/{{Cookie::get('userid')}}">
                            Incoming Request
                        </a>
                    </li>
                    @endif
                     
                </ul>
            </li>
        @endif
       
        <li class="">
            <!--<a href="#">
                <i class="icon mdi mdi-balance-wallet"></i><span class="nav-label">Reports</span>
            </a>-->
            <a href="#"><span class="nav-label">Reports</span><span class="fa fa-list-alt pull-right" ></span></a>
                <ul class="nav nav-second-level">
                    @if(\Cookie::get('user_type') ==1)
                  <!--   <li>
                        <a href="/commission-reports">
                            Commission
                        </a>
                    </li> -->
                    <li>
                        <a href="/transaction-reports">
                            Transaction
                        </a>
                    </li>
                    <li>
                        <a href="/wallet-reports">
                            Wallet
                        </a>
                    </li>
                    <li>
                        <a href="/request-reports">
                            Request
                        </a>
                    </li>
                    @endif
                    @if(\Cookie::get('user_type') ==2)
                   <!--  <li>
                        <a href="/transaction-reports">
                            Transaction
                        </a>
                    </li> -->
                    <li>
                        <a href="/wallet-reports">
                            Wallet
                        </a>
                    </li>


                   <li>
                        <a href="/request-reports">
                            Request
                        </a>
                    </li>
                    
                    @endif
                   
                </ul>
            </li>
            @if(\Cookie::get('user_type') ==1)
                <li>
                    <!--<a href="/refund">
                        <i class="icon mdi mdi-money"></i><span class="nav-label">Get Refund</span>
                    </a>-->
                    <a href="/refund"><span class="nav-label">Get Refund</span><span class="fa fa-money pull-right" ></span></a>
            
                </li>
            @endif
            @if($user->vendorDetails->type != 4 && $user->vendorDetails->type != 5 && $user->vendorDetails->type != 6 && $user->vendorDetails->type != 7 && $user->vendorDetails->type != 10 && $user->vendorDetails->type != 11 && $domain_data != 'dmt.mysuravi.com:8021' && $domain_data != 'dmt.lrprch.in:8021' && $domain_data != 'dmt.manjilgroup.com:8021' && $domain_data != 'dmt.indiapaysolution.com:8021' && $domain_data != 'dmt.samriddhifoundation.net.in:8021' && $domain_data != 'dmtservices.primagemarketing.com:8021' && $domain_data != 'dmt.amenitiesservices.in:8021' && $domain_data != 'dmt.ekiosk.in:8021' && $domain_data != 'dmt.aonehub.com' && $domain_data != 'dmt.zippays.in:8021' && $domain_data != 'dmtpearltek.digitalindiapayments.com:8021' && $domain_data != 'dmt.akpayments.in:8021' && $domain_data != 'dmt.oneindiapayments.com:8021')
             <!-- <li class="">
                
                 <a href="#"><span class="nav-label">SUPPORT</span><span class="fa fa-headphones pull-right" ></span></a>
                <ul class="nav nav-second-level">
                    <li><a ng-href="/support">REQUEST SUPPORT</a></li>
                    <li><a ng-href="/support-report">SUPPORT REPORT</a></li>
                 </ul>
              </li> -->

              <!-- <li class=""><a href="/ticket"><span>SUPPORT</span>
              <span class="label label-warning pull-right">NEW</span></a>
            </li> -->

            <li>
                    <a href="#"><span class="nav-label">SUPPORT</span><span class="fa fa-support pull-right" ></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="/add-ticket">Generate Ticket</a></li>
                        <li><a href="/all-ticket">All Tickets</a></li>
                    </ul>
                </li>
            @endif
            <!-- @if(\Cookie::get('user_type')  != 4 && \Cookie::get('user_type')  != 5 && \Cookie::get('user_type')  != 6 && \Cookie::get('user_type')  != 7 && \Cookie::get('user_type')  != 10 && \Cookie::get('user_type')  != 11) -->
             <!-- <li class="">
                
                 <a href="#"><span class="nav-label">SUPPORT</span><span class="fa fa-headphones pull-right" ></span></a>
                <ul class="nav nav-second-level">
                    <li><a ng-href="/support">REQUEST SUPPORT</a></li>
                    <li><a ng-href="/support-report">SUPPORT REPORT</a></li>
                 </ul>
              </li> -->

             <!--  <li class=""><a href="/ticket"><span>SUPPORT</span>
              <span class="label label-warning pull-right">NEW</span></a>
            </li> -->
                <!-- <li>
                    <a href="#"><span class="nav-label">SUPPORT</span><span class="fa fa-support pull-right" ></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="/add-ticket">Generate Ticket</a></li>
                        <li><a href="/all-ticket">All Tickets</a></li>
                    </ul>
                </li> 
            @endif-->
            <li class=""> 
                <!--<a href="#">
                    <i class="icon mdi mdi-settings"></i><span class="nav-label">Settings</span>
                </a>-->
                <a href="#"><span class="nav-label">Settings</span><span class="fa fa-cog pull-right" ></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/users/{{ \Cookie::get('userid') }}/profile">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="/users/{{\Cookie::get('userid')}}/actions/change-password">
                            Change Password
                        </a>
                    </li>
                </ul>
            </li>
    </ul>
</div>