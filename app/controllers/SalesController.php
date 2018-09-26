<?php

use Acme\Auth\Auth;
use Carbon\Carbon;
use Acme\Helper\Export;
class SalesController extends BaseController {

        public function GetAepsUserNames($id)
        {
              $headers = [
              'Accept' => 'application/json',
              'Content-Type' => 'application/json',
              'auth' => \Cookie::get('tracker')
            ];
            //$idarray=array('id'=>$id);
            $body = ['user_ids' => $id];
            
            $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/v1/user-detail-for-dmtsales', $headers, $body);

            if($response->body)
                {
                    return $response->body; 
                }else
                {
                    return $id; 
                }
        }

    public function getAgentSalesReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->type == 4){
            $parentId = DmtVendor::where('asm_id',Auth::user()->id)->lists('user_id');
            $agentuserId = DmtVendor::whereIn('parent_id',$parentId)
                                    ->where('type',1)
                                    ->lists('user_id'); 
        $agents=$this->GetAepsUserNames($agentuserId);
         $agentSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$agentuserId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $sumOfAgentAmount[] = 0 ;
            foreach ($agentSales as $agent) {
                $sumOfAgentAmount[$agent->user_id]=DB::table('dmt_transactions')
                ->where('dmt_transactions.user_id',$agent->user_id)
                ->sum('dmt_transactions.amount'); 
                //dd($sumOfAgentAmount);  
            } 
            foreach ($agents as $agent) {
                $agentName[$agent->id]=$agent->name;
                $agentPhone[$agent->id]=$agent->phone_no;
            } 
            return View::make('reports.agent-sales-reports',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentSum'=>$sumOfAgentAmount,'agentName'=>$agentName,'agentPhone'=>$agentPhone]);
        }
    }

    public function getDistributorSalesReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->type == 4)
        {
            $distributoruserId = DmtVendor::where('asm_id',Auth::user()->id)
            ->where('type',2)
            ->lists('user_id');
            $distributors=$this->GetAepsUserNames($distributoruserId);
            $distributorSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$distributoruserId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            foreach ($distributorData as $distributor) {
                $agent_list=DmtVendor::where('type',1)
            ->where('parent_id',$distributor->user_id)->lists('user_id');
                $sumOfAgentAmount[$distributor->id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');
                $countOfAgent[$distributor->id]=sizeof($agent_list);
            }  
            foreach ($distributors as $distributor) {
                $distributorName[$distributor->id]=$distributor->name;
                $distributorPhone[$distributor->id]=$distributor->phone_no;
            } 

        return View::make('reports.distributor-sales-reports',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorAgentSum'=>$sumOfAgentAmount,'distributorName'=>$distributorName,'distributorPhone'=>$distributorPhone]);
        }
    }

    public function getDistributorSalesDateReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->type == 4){
            $distributoruserId = DmtVendor::where('asm_id',Auth::user()->id)->lists('user_id');
            $distributors=$this->GetAepsUserNames($distributoruserId);
            $distributorSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$distributoruserId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            $distributorAgentFTDSum[] = 0;
            $distributorAgentLMTDSum[] = 0;
            $distributorAgentMTDSum[] = 0;
            foreach ($distributorData as $distributor) {
                $agent_list=DmtVendor::where('type',1)
            ->where('parent_id',$distributor->user_id)->lists('user_id');

                $distributorAgentFTDSum[$distributor->id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('amount');
                $distributorAgentLMTDSum[$distributor->id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('amount');
                $distributorAgentMTDSum[$distributor->id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('amount');
                $countOfAgent[$distributor->id]=sizeof($agent_list);
            }  

            foreach ($distributors as $distributor) {
                $distributorName[$distributor->id]=$distributor->name;
                $distributorPhone[$distributor->id]=$distributor->phone_no;
            } 

        return View::make('reports.distributor-sales-date-reports',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorAgentFTDSum'=>$distributorAgentFTDSum,'distributorAgentLMTDSum'=>$distributorAgentLMTDSum,'distributorAgentMTDSum'=>$distributorAgentMTDSum,'distributorName'=>$distributorName,'distributorPhone'=>$distributorPhone]);
        }
    }

    public function getAgentSalesDateReportForDistributor($user_id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->type == 4){
         $parentId = DmtVendor::where('parent_id',$user_id)->lists('user_id');

         $agents=$this->GetAepsUserNames($parentId); 
         $agentSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$parentId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $agentFTDSum[] = 0;
            $agentLMTDSum[] = 0;
            $agentMTDSum[] = 0;
            foreach ($agentSales as $agent) {
                $agentFTDSum[$agent->user_id]=DB::table('dmt_transactions')
                ->where('dmt_transactions.user_id',$agent->user_id)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('amount');
                $agentLMTDSum[$agent->user_id]=DB::table('dmt_transactions')
                ->where('dmt_transactions.user_id',$agent->user_id)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('amount');
                $agentMTDSum[$agent->user_id]=DB::table('dmt_transactions')
                ->where('dmt_transactions.user_id',$agent->user_id)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('amount');
            } 
            foreach ($agents as $agent) {
                $agentName[$agent->id]=$agent->name;
                $agentPhone[$agent->id]=$agent->phone_no;
            } 
            return View::make('reports.agent-sales-date-reports-for-distributor',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentFTDSum'=>$agentFTDSum,'agentLMTDSum'=>$agentLMTDSum,'agentMTDSum'=>$agentMTDSum,'agentName'=>$agentName,'agentPhone'=>$agentPhone]);
        }

    }



    public function getAgentSalesReportForDistributor($user_id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->type == 4){
        $parentId = DmtVendor::where('parent_id',$user_id)->lists('user_id');

         $agents=$this->GetAepsUserNames($parentId); 
         $agentSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$parentId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $sumOfAgentAmount[] = 0 ;
            foreach ($agentSales as $agent) {
                $sumOfAgentAmount[$agent->user_id]=DB::table('dmt_transactions')
                ->where('dmt_transactions.user_id',$agent->user_id)
                ->sum('dmt_transactions.amount'); 
                //dd($sumOfAgentAmount);  
            } 
            foreach ($agents as $agent) {
                $agentName[$agent->id]=$agent->name;
                $agentPhone[$agent->id]=$agent->phone_no;
            } 
            return View::make('reports.agent-sales-reports-for-distributor',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentSum'=>$sumOfAgentAmount,'agentName'=>$agentName,'agentPhone'=>$agentPhone]);
        }

    }

    public function getAgentExport(){
        $user = Auth::user();
        if($user->type == 4){
            $a[]=0;
            $parentId = Vendor::where('asm_id',Auth::user()->id)->lists('user_id');
            $agentId = Vendor::where('type',1)
                ->whereIn('parent_id',$parentId)
                ->lists('user_id');
            $agents=$this->GetAepsUserNames($agentId);
            //dd($agents);
            // $agents = array_map(function ($a) use ($agentId)
            // {
            //   $a->name = $a->name;
            //   $a->phone_no = $a->phone_no;
            //   $a->bc_agents = DmtVendor::whereIn('dmt_vendors.user_id',$agentId)->first('bc_agent'); 
            //     return $a;    
            // }, $agents);  
            $records = DB::table('dmt_vendors')
            ->join('dmt_transactions','dmt_transactions.user_id','=','dmt_vendors.user_id')
            ->whereIn('dmt_vendors.user_id',$agentId)
            ->select('dmt_vendors.bc_agent',DB::raw('SUM(dmt_transactions.amount) as amount'))
            ->groupBy('dmt_transactions.user_id')
            ->get();
        $datas= json_decode( json_encode($records), true); 
        $export_csv= new Export();
        $export_csv->exportData($datas,"agent-sales-report-");  
        }
    }

    
    public function getDistributorExport(){
        $user = Auth::user();
        if($user->type == 4){
            $distributors = Vendor::where('type',2)
            ->where('asm_id',Auth::user()->id)
            ->lists('user_id');  
            $records = DB::table('dmt_vendors')
            ->join('users','users.id','=','dmt_vendors.user_id')
            ->join('dmt_transactions','dmt_transactions.user_id','=','dmt_vendors.user_id')
            ->whereIn('dmt_vendors.user_id',$distributors)
            ->select('users.name as DistributorName','dmt_vendors.bc_agent as Code','users.phone_no as MobileNo')
            ->get();
            //dd($records);
        $datas= json_decode( json_encode($records), true); 
        $export_csv= new Export();
        $export_csv->exportData($datas,"distributor-sales-report-");
        }
    }


    // public function getDistributorExport(){
    //     $user = Auth::user();
    //     if($user->type == 4){
    //         $state = Vendor::where('user_id',Auth::user()->id)->pluck('state');
    //         $distributorId = Vendor::where('type',2)->lists('user_id');  
    //     }
    //     $records = DB::table('dmt_vendors')
    //         ->join('users','users.id','=','dmt_vendors.user_id')
    //         ->join('dmt_transactions','dmt_transactions.user_id','=','dmt_vendors.user_id')
    //         ->select('*', DB::raw('SUM(dmt_transactions.amount) as amount'))
    //         ->groupBy('dmt_transactions.user_id')
    //         ->whereIn('dmt_vendors.user_id',$distributorId)
    //         ->where('dmt_vendors.state',$state)
    //         ->get();
    //         $distributorAgentCount;
    //         $distributorAgentSum;
    //         foreach($distributorId  as $distributor)
    //         {
    //             $distributorAgentCount[$distributor]=DB::table('dmt_vendors')
    //         ->where('dmt_vendors.type',1)
    //         ->where('dmt_vendors.parent_id',$distributor)
    //         ->count('dmt_vendors.user_id');

    //         $agentIds = Vendor::where('type',1)
    //         ->where('parent_id',$distributor)->lists('user_id');
    //             $distributorAgentSum[$distributor]=DB::table('dmt_transactions')
    //             ->whereIn('dmt_transactions.user_id',$agentIds)
    //             ->sum('dmt_transactions.amount');
    //         }
    //          $records->agentAmount=DB::table('dmt_vendors')
    //         ->join('dmt_transactions','dmt_transactions.user_id','=','dmt_vendors.user_id')
    //         ->whereIn('dmt_vendors.parent_id',$distributorId)
    //         ->select('*', DB::raw('SUM(dmt_transactions.amount) as amount'))->get();

    //         $datas= json_decode( json_encode($records), true); 
    //         $export_csv= new Export();
    //         $export_csv->exportData($datas,"distributor-sales-report-");
    // }


    /*********************Area Sales Officer************************/

    public function getAreaSalesOfficerReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 5)
        {
            $salesExecutiveuserId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $salesExecutives=$this->GetAepsUserNames($salesExecutiveuserId);

            $salesExecutiveSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            
            $countOfDistributor[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {;
                $distributor_list=DmtVendor::where('type',2)
                ->where('asm_id',$salesexecutive->user_id)
                ->lists('user_id');
                
                $countOfDistributor[$salesexecutive->user_id]=sizeof($distributor_list);

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');


                $sumOfAgentAmount[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');
            }

            if(isset($salesExecutives[0]->message)){
                    $salesExecutiveName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($salesExecutives[0]->name)){
                foreach ($salesExecutives as $salesExecutive) {
                    $salesExecutiveName[$salesExecutive->id]=$salesExecutive->name;
                } 
            }

            return View::make('reports.sales-executive-area-sales-officer-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData, 'countOfDistributor'=>$countOfDistributor, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'salesExecutiveName'=>$salesExecutiveName]);

        }
    }

    public function getSalesExecutiveReportForAreaSalesOfficer($id){
       Paginator::setPageName('page');
       $user = Auth::user();
       if($user->vendorDetails->type == 5 || $user->vendorDetails->type == 6 || $user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){
            $parentId = DmtVendor::where('asm_id',$id)
            ->where('type',2)
            ->lists('user_id');

            $distributors=$this->GetAepsUserNames($parentId);

            $distributorSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$parentId)
            ->paginate(100);

            $distributorSales = $distributorSalesObj->getItems();   
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            foreach ($distributorSales as $distributorSale) {

                $agent_list=DmtVendor::where('type',1)
                ->where('parent_id',$distributorSale->user_id)
                ->whereNotNull('bc_agent')
                ->lists('user_id');
                $countOfAgent[$distributorSale->user_id]=sizeof($agent_list);

                $sumOfAgentAmount[$distributorSale->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');
                //dd($sumOfAgentAmount);
            }
  
            if(isset($distributors[0]->message)){
                    $distributorName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($distributors[0]->name)){
                foreach ($distributors as $distributor) {
                    $distributorName[$distributor->id]=$distributor->name;
                }
            }

            return View::make('reports.sales-executive-reports-for-area-sales-officer',['distributorSales' => $distributorSales, 'countOfAgent'=>$countOfAgent,'distributorSalesObj' => $distributorSalesObj,'agentSum'=>$sumOfAgentAmount, 'distributorName' => $distributorName]);
        }
    }

    public function getAgentReportForAreaSalesOfficer($id){
       Paginator::setPageName('page');
        $user = Auth::user();
       if($user->vendorDetails->type == 5 || $user->vendorDetails->type == 6 || $user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){
            $parentId = DmtVendor::where('parent_id',$id)->lists('user_id');

            $agents=$this->GetAepsUserNames($parentId);

            $agentSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$parentId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $sumOfAgentAmount[] = 0 ;
            foreach ($agentSales as $agent) {

                $sumOfAgentAmount[$agent->user_id]=DB::table('dmt_transactions')
                ->where('dmt_transactions.user_id',$agent->user_id)
                ->sum('dmt_transactions.amount');
            }

            if(isset($agents[0]->message)){
                    $agentName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($agents[0]->name)){
                foreach ($agents as $agent) {
                    $agentName[$agent->id]=$agent->name;
                } 
            }

            return View::make('reports.agent-reports-for-area-sales-officer',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentSum'=>$sumOfAgentAmount, 'parentId'=>$id, 'agentName'=>$agentName]);
        }
    }

    public function getDistributorAreaSalesOfficerReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
       if($user->vendorDetails->type == 5){

            $salesExecutiveuserId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $distributors=$this->GetAepsUserNames($distributoruserId);

            $distributorSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$distributoruserId)
            ->paginate(100);

            $distributorSales=$distributorSalesObj->getItems();

            $countOfAgent[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($distributorSales as $distributorSale) {

                $agent_list=DmtVendor::where('type',1)
                ->where('parent_id',$distributorSale->user_id)
                ->whereNotNull('bc_agent')
                ->lists('user_id');
                $sumOfAgentAmount[$distributorSale->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');
                $countOfAgent[$distributorSale->user_id]=sizeof($agent_list);
            }

            if(isset($distributors[0]->message)){
                    $distributorName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($distributors[0]->name)){
                foreach ($distributors as $distributor) {
                    $distributorName[$distributor->id]=$distributor->name;
                } 
            }

            return View::make('reports.distributor-area-sales-officer-reports',['distributorSales' => $distributorSales,'distributorSalesObj' => $distributorSalesObj, 'countOfAgent'=>$countOfAgent,'agentSum'=>$sumOfAgentAmount, 'distributorName'=>$distributorName]);
        }
    }

    public function getSalesExecutiveSalesDateReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 5){

            $salesExecutiveuserId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $salesExecutives=$this->GetAepsUserNames($salesExecutiveuserId);

           $salesExecutiveSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);
            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $salesExecutiveFTDSum[] = 0;
            $salesExecutiveLMTDSum[] = 0;
            $salesExecutiveMTDSum[] = 0;
            $countOfDistributor[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=DmtVendor::where('type',2)
                ->where('asm_id',$salesexecutive->user_id)
                ->lists('user_id');
                $countOfDistributor[$salesexecutive->user_id]=sizeof($distributor_list);

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $salesExecutiveFTDSum[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $salesExecutiveLMTDSum[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $salesExecutiveMTDSum[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');

                
            }
  
            if(isset($salesExecutives[0]->message)){
                    $salesExecutiveName = '';
                    $salesExecutiveMTDSum = '';
                    $salesExecutiveLMTDSum = '';
                    $salesExecutiveFTDSum = '';
            }
            if(isset($salesExecutives[0]->name)){
                foreach ($salesExecutives as $salesExecutive) {
                    $salesExecutiveName[$salesExecutive->id]=$salesExecutive->name;
                } 
            }

            
        return View::make('reports.sales-executive-area-sales-officer-date-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData,'countOfDistributor'=>$countOfDistributor,'salesExecutiveName'=>$salesExecutiveName,'salesExecutiveFTDSum'=>$salesExecutiveFTDSum,'salesExecutiveLMTDSum'=>$salesExecutiveLMTDSum,'salesExecutiveMTDSum'=>$salesExecutiveMTDSum]);
        }
    }

    public function getDistributorSalesExecutiveSalesDateReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 5 || $user->vendorDetails->type == 6 || $user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){

            $parentId = DmtVendor::where('asm_id',$id)
            ->where('type',2)
            ->lists('user_id');

            $distributors=$this->GetAepsUserNames($parentId);
 
            $distributorSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$parentId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();

            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            $distributorFTDSum[] = 0;
            $distributorLMTDSum[] = 0;
            $distributorMTDSum[] = 0;

            foreach ($distributorData as $distributor) {
                $agent_list=DmtVendor::where('type',1)
                ->where('parent_id',$distributor->user_id)
                ->whereNotNull('bc_agent')
                ->lists('user_id');
                //dd(Carbon::parse('-1 month'));
                $distributorFTDSum[$distributor->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');
                $distributorLMTDSum[$distributor->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');
                $distributorMTDSum[$distributor->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');
                $countOfAgent[$distributor->user_id]=sizeof($agent_list);
            }

            if(isset($distributors[0]->message)){
                    $distributorName = '';
                    $distributorMTDSum = '';
                    $distributorLMTDSum = '';
                    $distributorFTDSum = '';
            }
            if(isset($distributors[0]->name)){
                foreach ($distributors as $distributor) {
                    $distributorName[$distributor->id]=$distributor->name;
                }  
            }

              
        return View::make('reports.distributor-date-report-for-area-sales-officer',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorFTDSum'=>$distributorFTDSum,'distributorLMTDSum'=>$distributorLMTDSum,'distributorMTDSum'=>$distributorMTDSum, 'distributorName'=>$distributorName]);
        }
    }

    /*****************************Area Sales Manager Start*****************************/
    
    public function getAreaSalesManagerReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 6){

            $areaSalesOfficerId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficers=$this->GetAepsUserNames($areaSalesOfficerId);

            $areaSalesOfficerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesExecutive[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=DmtVendor::where('type',4)
                ->where('parent_id',$areaSalesOfficer->user_id)
                ->lists('user_id');
                $countOfSalesExecutive[$areaSalesOfficer->user_id]=sizeof($sales_executive_list);
                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');
            }

            if(isset($areaSalesOfficers[0]->message)){
                    $areaSalesOfficerName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($areaSalesOfficers[0]->name)){
                foreach ($areaSalesOfficers as $areaSalesOfficer) {
                    $areaSalesOfficerName[$areaSalesOfficer->id]=$areaSalesOfficer->name;
                } 
            }

            return View::make('reports.area-salaes-officer-area-sales-manager-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData, 'countOfSalesExecutive'=>$countOfSalesExecutive, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'areaSalesOfficerName'=>$areaSalesOfficerName]);
        } 
    }

    public function getSalesExecutiveReportForAreaSalesManager($id){
        Paginator::setPageName('page');
         $user = Auth::user();
        if($user->vendorDetails->type == 6 || $user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11)
        {
            $salesExecutiveuserId = DmtVendor::where('parent_id',$id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $salesExecutives=$this->GetAepsUserNames($salesExecutiveuserId);

            $salesExecutiveSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            
            $countOfDistributor[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {;
                $distributor_list=DmtVendor::where('type',2)
                ->where('asm_id',$salesexecutive->user_id)
                ->lists('user_id');
                
                $countOfDistributor[$salesexecutive->user_id]=sizeof($distributor_list);

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');


                $sumOfAgentAmount[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');
            }

            if(isset($salesExecutives[0]->message)){
                    $salesExecutiveName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($salesExecutives[0]->name)){
                foreach ($salesExecutives as $salesExecutive) {
                    $salesExecutiveName[$salesExecutive->id]=$salesExecutive->name;
                }
            }

            return View::make('reports.sales-executive-area-sales-officer-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData, 'countOfDistributor'=>$countOfDistributor, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'salesExecutiveName'=>$salesExecutiveName]);
 
        }
    }

    public function getSalesExecutiveSalesManagerReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 6){

            $areaSalesOfficerId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $salesexecutives=$this->GetAepsUserNames($salesExecutiveuserId);

           $salesExecutiveSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $countOfDistributor[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=DmtVendor::where('type',2)
                ->where('asm_id',$salesexecutive->user_id)
                ->lists('user_id');
                $countOfDistributor[$salesexecutive->user_id]=sizeof($distributor_list);

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');
            }

            if(isset($salesexecutives[0]->message)){
                    $salesexecutiveName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($salesexecutives[0]->name)){
                foreach ($salesexecutives as $salesexecutive) {
                    $salesexecutiveName[$salesexecutive->id]=$salesexecutive->name;
                }
            }

            return View::make('reports.sales-executive-area-sales-manager-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData, 'countOfDistributor'=>$countOfDistributor, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'salesexecutiveName' => $salesexecutiveName]);
        }
    }


    public function getSalesOfficeAreaSalesManagerSalesDateReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 6){

            $areaSalesOfficerId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesofficers = $this->GetAepsUserNames($areaSalesOfficerId);

            $areaSalesOfficerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();
            $countOfSalesExecutive[] = 0;
            $areaSalesOfficerFTDSum[] =0;
            $areaSalesOfficerLMTDSum[]=0;
            $areaSalesOfficerMTDSum[]=0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=DmtVendor::where('type',4)
                ->where('parent_id',$areaSalesOfficer->user_id)
                ->lists('user_id');

                $countOfSalesExecutive[$areaSalesOfficer->user_id]=sizeof($sales_executive_list);

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesOfficerFTDSum[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $areaSalesOfficerLMTDSum[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $areaSalesOfficerMTDSum[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');
            }

            if(isset($areasalesofficers[0]->message)){
                    $areasalesofficerName = '';
                    $areaSalesOfficerMTDSum = '';
                    $areaSalesOfficerLMTDSum = '';
                    $areaSalesOfficerFTDSum = '';
            }
            if(isset($areasalesofficers[0]->name)){
                foreach ($areasalesofficers as $areasalesofficer) {
                    $areasalesofficerName[$areasalesofficer->id]=$areasalesofficer->name;
                }   
            }

        return View::make('reports.area-sales-officer-area-sales-manager-date-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData,'countOfSalesExecutive'=>$countOfSalesExecutive,'areaSalesOfficerFTDSum'=>$areaSalesOfficerFTDSum,'areaSalesOfficerLMTDSum'=>$areaSalesOfficerLMTDSum,'areaSalesOfficerMTDSum'=>$areaSalesOfficerMTDSum, 'areasalesofficerName'=>$areasalesofficerName]);
        }
    }

    public function getSalesExecutiveAreaSalesManagerSalesDateReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 6 || $user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){

            $salesExecutiveuserId = DmtVendor::where('parent_id',$id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $salesExecutives=$this->GetAepsUserNames($salesExecutiveuserId);

           $salesExecutiveSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);
            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $salesExecutiveFTDSum[] = 0;
            $salesExecutiveLMTDSum[] = 0;
            $salesExecutiveMTDSum[] = 0;
            $countOfDistributor[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=DmtVendor::where('type',2)
                ->where('asm_id',$salesexecutive->user_id)
                ->lists('user_id');
                $countOfDistributor[$salesexecutive->user_id]=sizeof($distributor_list);

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $salesExecutiveFTDSum[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $salesExecutiveLMTDSum[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $salesExecutiveMTDSum[$salesexecutive->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');
            }

            if(isset($salesExecutives[0]->message)){
                    $salesExecutiveName = '';
                    $salesExecutiveMTDSum = '';
                    $salesExecutiveLMTDSum = '';
                    $salesExecutiveFTDSum = '';
            }
            if(isset($salesExecutives[0]->name)){
                foreach ($salesExecutives as $salesExecutive) {
                    $salesExecutiveName[$salesExecutive->id]=$salesExecutive->name;
                }  
            }

            
        return View::make('reports.sales-executive-area-sales-officer-date-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData,'countOfDistributor'=>$countOfDistributor,'salesExecutiveName'=>$salesExecutiveName,'salesExecutiveFTDSum'=>$salesExecutiveFTDSum,'salesExecutiveLMTDSum'=>$salesExecutiveLMTDSum,'salesExecutiveMTDSum'=>$salesExecutiveMTDSum]);
        }
    }

    /*****************************Cluster Head Start*****************************/

    public function getClusterHeadReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 7){

            $areaSalesManagerId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesmanagers=$this->GetAepsUserNames($areaSalesManagerId);

            $areaSalesManagerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesOfficer[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=DmtVendor::where('type',5)
                ->where('parent_id',$areaSalesManager->user_id)
                ->lists('user_id');
                $countOfSalesOfficer[$areaSalesManager->user_id]=sizeof($area_sales_officer_list);

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');

            }

            if(isset($areasalesmanagers[0]->message)){
                    $areaSalesManagerName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($areasalesmanagers[0]->name)){
                foreach ($areasalesmanagers as $areasalesmanager) {
                    $areaSalesManagerName[$areasalesmanager->id]=$areasalesmanager->name;
                } 
            }

            return View::make('reports.area-salaes-manager-cluster-head-reports',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData, 'countOfSalesOfficer'=>$countOfSalesOfficer, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'areaSalesManagerName'=>$areaSalesManagerName]);
        } 
    }

    public function getAreaSalesOfficerClustorHeadReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){

            $areaSalesOfficerId = DmtVendor::where('parent_id',$id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficers=$this->GetAepsUserNames($areaSalesOfficerId);

            $areaSalesOfficerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();
            
            //dd($areaSalesOfficerData);

            $countOfSalesExecutive[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=DmtVendor::where('type',4)
                ->where('parent_id',$areaSalesOfficer->user_id)
                ->lists('user_id');
                $countOfSalesExecutive[$areaSalesOfficer->user_id]=sizeof($sales_executive_list);
                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');
            }

            if(isset($areaSalesOfficers[0]->message)){
                    $areaSalesOfficerName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($areaSalesOfficers[0]->name)){
                foreach ($areaSalesOfficers as $areaSalesOfficer) {
                    $areaSalesOfficerName[$areaSalesOfficer->id]=$areaSalesOfficer->name;
                } 
            }
            

            return View::make('reports.area-salaes-officer-area-sales-manager-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData, 'countOfSalesExecutive'=>$countOfSalesExecutive, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'areaSalesOfficerName'=>$areaSalesOfficerName]);
        } 
    }

    public function getAreaSalesOfficerForClustorHeadReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 7){

            $areaSalesManagerId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficers=$this->GetAepsUserNames($areaSalesOfficerId);

            $areaSalesOfficerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesExecutive[] = 0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=DmtVendor::where('type',4)
                ->where('parent_id',$areaSalesOfficer->user_id)
                ->lists('user_id');
                $countOfSalesExecutive[$areaSalesOfficer->user_id]=sizeof($sales_executive_list);

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');
            }

            if(isset($areaSalesOfficers[0]->message)){
                    $areaSalesOfficerName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($areaSalesOfficers[0]->name)){
                foreach ($areaSalesOfficers as $areaSalesOfficer) {
                    $areaSalesOfficerName[$areaSalesOfficer->id]=$areaSalesOfficer->name;
                } 
            }
            
            
            return View::make('reports.area-salaes-officer-area-sales-manager-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData, 'countOfSalesExecutive'=>$countOfSalesExecutive, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'areaSalesOfficerName'=>$areaSalesOfficerName]);
        } 
    }

    public function getSalesManagerClusterHeadSalesDateReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 7){

           $areaSalesManagerId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesmanagers=$this->GetAepsUserNames($areaSalesManagerId);

            $areaSalesManagerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesOfficer[] = 0;
            $areaSalesManagerFTDSum[] = 0;
            $areaSalesManagerLMTDSum[] = 0;
            $areaSalesManagerMTDSum[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=DmtVendor::where('type',5)
                ->where('parent_id',$areaSalesManager->user_id)
                ->lists('user_id');
                $countOfSalesOfficer[$areaSalesManager->user_id]=sizeof($area_sales_officer_list);

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesManagerFTDSum[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $areaSalesManagerLMTDSum[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $areaSalesManagerMTDSum[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');
            }

            if(isset($areasalesmanagers[0]->message)){
                    $areaSalesManagerName = '';
                    $areaSalesManagerMTDSum = '';
                    $areaSalesManagerLMTDSum = '';
                    $areaSalesManagerFTDSum = '';
            }
            if(isset($areasalesmanagers[0]->name)){
                foreach ($areasalesmanagers as $areasalesmanager) {
                    $areaSalesManagerName[$areasalesmanager->id]=$areasalesmanager->name;
                } 
            }

        return View::make('reports.area-sales-manager-cluster-head-date-reports',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData,'countOfSalesOfficer'=>$countOfSalesOfficer,'areaSalesManagerFTDSum'=>$areaSalesManagerFTDSum,'areaSalesManagerLMTDSum'=>$areaSalesManagerLMTDSum,'areaSalesManagerMTDSum'=>$areaSalesManagerMTDSum, 'areaSalesManagerName'=>$areaSalesManagerName]);
        }
    }

    public function getSalesOfficeClusterHeadDateReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 7 || $user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){

            $areaSalesOfficerId = DmtVendor::where('parent_id',$id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesofficers = $this->GetAepsUserNames($areaSalesOfficerId);

            $areaSalesOfficerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();
            $countOfSalesExecutive[] = 0;
            $areaSalesOfficerFTDSum[] =0;
            $areaSalesOfficerLMTDSum[]=0;
            $areaSalesOfficerMTDSum[]=0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=DmtVendor::where('type',4)
                ->where('parent_id',$areaSalesOfficer->user_id)
                ->lists('user_id');

                $countOfSalesExecutive[$areaSalesOfficer->user_id]=sizeof($sales_executive_list);

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesOfficerFTDSum[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $areaSalesOfficerLMTDSum[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $areaSalesOfficerMTDSum[$areaSalesOfficer->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');
            }

            if(isset($areasalesofficers[0]->message)){
                    $areasalesofficerName = '';
                    $areaSalesOfficerMTDSum = '';
                    $areaSalesOfficerLMTDSum = '';
                    $areaSalesOfficerFTDSum = '';
            }
            if(isset($areasalesofficers[0]->name)){
                foreach ($areasalesofficers as $areasalesofficer) {
                    $areasalesofficerName[$areasalesofficer->id]=$areasalesofficer->name;
                } 
            }

        return View::make('reports.area-sales-officer-area-sales-manager-date-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData,'countOfSalesExecutive'=>$countOfSalesExecutive,'areaSalesOfficerFTDSum'=>$areaSalesOfficerFTDSum,'areaSalesOfficerLMTDSum'=>$areaSalesOfficerLMTDSum,'areaSalesOfficerMTDSum'=>$areaSalesOfficerMTDSum, 'areasalesofficerName'=>$areasalesofficerName]);
        }
    }

    /*****************************State Head Start*****************************/

    public function getStateHeadReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 10){

            $clusterHeadReportId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterheads = $this->GetAepsUserNames($clusterHeadReportId);

            $clusterHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadData=$clusterHeadSalesObj->getItems();

            $countOfSalesManager[] = 0;
            foreach ($clusterHeadData as $clusterHead) {

                $area_sales_manager_list=DmtVendor::where('type',6)
                ->where('parent_id',$clusterHead->user_id)
                ->lists('user_id');
                $countOfSalesManager[$clusterHead->user_id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=DmtVendor::where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->sum('dmt_transactions.amount');

            }

            if(isset($clusterheads[0]->message)){
                    $clusterheadName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($clusterheads[0]->name)){
                foreach ($clusterheads as $clusterhead) {
                    $clusterheadName[$clusterhead->id]=$clusterhead->name;
                } 
            }

            return View::make('reports.cluster-head-state-head-reports',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadData, 'countOfSalesManager'=>$countOfSalesManager, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'clusterheadName'=>$clusterheadName]);
        } 
    }

    public function getAreaSalesManagerForClusterHeadReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){

            $areaSalesManagerId = DmtVendor::where('parent_id',$id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesmanagers =  $this->GetAepsUserNames($areaSalesManagerId);

            $areaSalesManagerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            $countOfAreaSalesOfficer[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                    $area_sales_officer_list=DmtVendor::where('type',5)
                ->where('parent_id',$areaSalesManager->user_id)
                ->lists('user_id');

                $countOfAreaSalesOfficer[$areaSalesManager->user_id]=sizeof($area_sales_officer_list);

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');
            }

            if(isset($areasalesmanagers[0]->message)){
                    $areasalesmanagerName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($areasalesmanagers[0]->name)){
                foreach ($areasalesmanagers as $areasalesmanager) {
                    $areasalesmanagerName[$areasalesmanager->id]=$areasalesmanager->name;
                }
            }

            return View::make('reports.area-sales-manager-reports-for-state-head',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData, 'countOfAreaSalesOfficer'=>$countOfAreaSalesOfficer, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'areasalesmanagerName' => $areasalesmanagerName]);
        } 
    }

    public function getAreaSalesManagerForStateHeadReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 10){

            $clusterHeadReportId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesmanagers =  $this->GetAepsUserNames($areaSalesManagerId);

            $areaSalesManagerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);
            $sumOfAgentAmount[] = 0;
            $countOfAreaSalesOfficer[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=DmtVendor::where('type',5)
                ->where('parent_id',$areaSalesManager->user_id)
                ->lists('user_id');
                $countOfAreaSalesOfficer[$areaSalesManager->user_id]=sizeof($area_sales_officer_list);

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');
            }

            if(isset($areasalesmanagers[0]->message)){
                    $areasalesmanagerName = '';
                    $sumOfAgentAmount = '';
            }
            if(isset($areasalesmanagers[0]->name)){
                foreach ($areasalesmanagers as $areasalesmanager) {
                    $areasalesmanagerName[$areasalesmanager->id]=$areasalesmanager->name;
                }
            }

            return View::make('reports.area-sales-manager-reports-for-state-head',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData, 'countOfAreaSalesOfficer'=>$countOfAreaSalesOfficer, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'areasalesmanagerName'=>$areasalesmanagerName]);
        } 
    }

    public function getClusterHeadStateHeadSalesDateReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 10){

           $clusterHeadReportId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterheads = $this->GetAepsUserNames($clusterHeadReportId);

            $clusterHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadSalesData=$clusterHeadSalesObj->getItems();

            //dd($areaSalesOfficerData);
            $clusterHeadFTDSum[] = 0;
            $clusterHeadLMTDSum[] = 0;
            $clusterHeadMTDSum[] = 0;
            $countOfSalesManager[] = 0;
            foreach ($clusterHeadSalesData as $clusterHead) {
                $area_sales_manager_list=Vendor::where('type',6)
                ->where('parent_id',$clusterHead->user_id)
                ->lists('user_id');
                $countOfSalesManager[$clusterHead->user_id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=DmtVendor::where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $clusterHeadFTDSum[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $clusterHeadLMTDSum[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $clusterHeadMTDSum[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');

            }

            if(isset($clusterheads[0]->message)){
                $clusterheadName = '';
                $clusterHeadMTDSum = '';
                $clusterHeadLMTDSum = '';
                $clusterHeadFTDSum = '';
            }
            if(isset($clusterheads[0]->name)){
                foreach ($clusterheads as $clusterhead) {
                    $clusterheadName[$clusterhead->id]=$clusterhead->name;
                }
            }

            

        return View::make('reports.cluster-head-state-head-date-reports',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadSalesData,'countOfSalesManager'=>$countOfSalesManager,'clusterHeadFTDSum'=>$clusterHeadFTDSum,'clusterHeadLMTDSum'=>$clusterHeadLMTDSum,'clusterHeadMTDSum'=>$clusterHeadMTDSum, 'clusterheadName'=>$clusterheadName]);
        }
    }

    public function getAreaSalesManagerClusterHeadSalesDateReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 10 || $user->vendorDetails->type == 11){

           $areaSalesManagerId = DmtVendor::where('parent_id',$id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areasalesmanagers=$this->GetAepsUserNames($areaSalesManagerId);

            $areaSalesManagerSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesOfficer[] = 0;
            $areaSalesManagerFTDSum[] = 0;
            $areaSalesManagerLMTDSum[] = 0;
            $areaSalesManagerMTDSum[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=DmtVendor::where('type',5)
                ->where('parent_id',$areaSalesManager->user_id)
                ->lists('user_id');
                $countOfSalesOfficer[$areaSalesManager->user_id]=sizeof($area_sales_officer_list);

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesManagerFTDSum[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $areaSalesManagerLMTDSum[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $areaSalesManagerMTDSum[$areaSalesManager->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');
            }

            if(isset($areasalesmanagers[0]->message)){
                $areaSalesManagerName = '';
                $areaSalesManagerMTDSum = '';
                $areaSalesManagerLMTDSum = '';
                $areaSalesManagerFTDSum = '';
            }
            if(isset($areasalesmanagers[0]->name)){
                foreach ($areasalesmanagers as $areasalesmanager) {
                    $areaSalesManagerName[$areasalesmanager->id]=$areasalesmanager->name;
                }
            }

        return View::make('reports.area-sales-manager-cluster-head-date-reports',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData,'countOfSalesOfficer'=>$countOfSalesOfficer,'areaSalesManagerFTDSum'=>$areaSalesManagerFTDSum,'areaSalesManagerLMTDSum'=>$areaSalesManagerLMTDSum,'areaSalesManagerMTDSum'=>$areaSalesManagerMTDSum, 'areaSalesManagerName'=>$areaSalesManagerName]);
        }
    }

    /*****************************Regional Head Start*****************************/

    public function getRegionalHeadReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 11){

            $stateHeadReportId = DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',10)
            ->lists('user_id');

            $clusterHeadReportId = DmtVendor::whereIn('parent_id',$stateHeadReportId)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $stateheads=$this->GetAepsUserNames($stateHeadReportId);

            $stateHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$stateHeadReportId)
            ->paginate(100);

            $stateHeadData=$stateHeadSalesObj->getItems();

            $countOfClusterHead[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($stateHeadData as $stateHead) {

                $cluster_head_id=DmtVendor::where('type',7)
                ->where('parent_id',$stateHead->user_id)
                ->lists('user_id');

                $countOfClusterHead[$stateHead->user_id]=sizeof($cluster_head_id);

                $areaSalesManager_id=DmtVendor::where('type',6)
                ->whereIn('parent_id',$cluster_head_id)
                ->lists('user_id');

                $areaSalesOfficer_id=DmtVendor::where('type',5)
                ->whereIn('parent_id',$areaSalesManager_id)
                ->lists('user_id');

                $salesExecutive_id=DmtVendor::where('type',4)
                ->whereIn('parent_id',$areaSalesOfficer_id)
                ->lists('user_id');

                $distributor_id=DmtVendor::where('type',2)
                ->whereIn('asm_id',$salesExecutive_id)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_id)
                ->lists('user_id');

                $sumOfAgentAmount[$stateHead->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');
            }
            if(isset($stateheads[0]->message)){
                $stateheadName = '';
                $sumOfAgentAmount = '';
            }
            if(isset($stateheads[0]->name)){
                foreach ($stateheads as $statehead) {
                    $stateheadName[$statehead->id]=$statehead->name;
                }
            }

            return View::make('reports.state-head-regional-head-reports',['stateHeadSalesObj'=>$stateHeadSalesObj,'stateHeadSales' => $stateHeadData, 'countOfClusterHead'=>$countOfClusterHead, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'stateheadName' => $stateheadName]);
        } 
    } 

    public function getClusterHeadForStateHeadReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 11){

            $clusterHeadReportId = DmtVendor::where('parent_id',$id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterheads = $this->GetAepsUserNames($clusterHeadReportId);



            $clusterHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadData=$clusterHeadSalesObj->getItems();

            $countOfAreaSalesManager[] = 0;

                foreach ($clusterHeadData as $clusterHead) {

                    $area_sales_manager_list=Vendor::where('type',6)
                    ->where('parent_id',$clusterHead->user_id)
                    ->lists('user_id');
                    $countOfAreaSalesManager[$clusterHead->user_id]=sizeof($area_sales_manager_list);

                    $area_sales_officer_list=DmtVendor::where('type',5)
                    ->whereIn('parent_id',$area_sales_manager_list)
                    ->lists('user_id');

                    $sales_executive_list=DmtVendor::where('type',4)
                    ->whereIn('parent_id',$area_sales_officer_list)
                    ->lists('user_id');

                    $distributor_list=DmtVendor::where('type',2)
                    ->whereIn('asm_id',$sales_executive_list)
                    ->lists('user_id');

                    $agent_list=DmtVendor::where('type',1)
                    ->whereIn('parent_id',$distributor_list)
                    ->lists('user_id');

                    $sumOfAgentAmount[$clusterHead->user_id]=DB::table('dmt_transactions')
                        ->whereIn('dmt_transactions.user_id',$agent_list)
                        ->sum('dmt_transactions.amount');

                }
                if(isset($clusterheads[0]->message)){
                    $clusterheadName = '';
                    $sumOfAgentAmount = '';
                }
                if(isset($clusterheads[0]->name)){
                    foreach ($clusterheads as $clusterhead) {
                        $clusterheadName[$clusterhead->id]=$clusterhead->name;
                    }
                }

            return View::make('reports.cluster-head-reports-for-regional-head',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadData, 'countOfAreaSalesManager'=>$countOfAreaSalesManager, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'clusterheadName'=>$clusterheadName]);
        } 
    }

    public function getClusterHeadForRegionalHeadReport(){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 11){

            $stateHeadReportId= Vendor::where('parent_id',Auth::user()->id)
            ->where('type',10)
            ->lists('user_id');

            $clusterHeadReportId = Vendor::whereIn('parent_id',$stateHeadReportId)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterheads = $this->GetAepsUserNames($clusterHeadReportId);

            $clusterHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadData=$clusterHeadSalesObj->getItems();

            $countOfAreaSalesManager[] = 0;
            foreach ($clusterHeadData as $clusterHead) {
                $area_sales_manager_list=Vendor::where('type',6)
                ->where('parent_id',$clusterHead->user_id)
                ->lists('user_id');
                $countOfAreaSalesManager[$clusterHead->user_id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=DmtVendor::where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$clusterHead->user_id]=DB::table('dmt_transactions')
                    ->whereIn('dmt_transactions.user_id',$agent_list)
                    ->sum('dmt_transactions.amount');

            }

            if(isset($clusterheads[0]->message)){
                $clusterheadName = '';
                $sumOfAgentAmount = '';
            }
            if(isset($clusterheads[0]->name)){
                foreach ($clusterheads as $clusterhead) {
                    $clusterheadName[$clusterhead->id]=$clusterhead->name;
                }
            }

            return View::make('reports.cluster-head-reports-for-regional-head',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadData, 'countOfAreaSalesManager'=>$countOfAreaSalesManager, 'sumOfAgentAmount'=>$sumOfAgentAmount, 'clusterheadName'=>$clusterheadName]);
        } 
    }

    public function getStateHeadRegionalHeadSalesDateReport(){
            
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 11){

            $stateHeadReportId= DmtVendor::where('parent_id',Auth::user()->id)
            ->where('type',10)
            ->lists('user_id');

            $clusterHeadReportId = DmtVendor::whereIn('parent_id',$stateHeadReportId)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $stateheads=$this->GetAepsUserNames($stateHeadReportId);

            $stateHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$stateHeadReportId)
            ->paginate(100);

            $stateHeadSalesData=$stateHeadSalesObj->getItems();
            $stateHeadFTDSum[] = 0;
            $stateHeadLMTDSum[] = 0;
            $stateHeadMTDSum[] = 0;
            $sumOfAgentAmount[] = 0;
            $countOfClusterHead[] = 0;
            $i=0;
            foreach ($stateHeadSalesData as $stateHead) {


                $cluster_head_list=DmtVendor::where('type',7)
                ->where('parent_id',$stateHead->user_id)
                ->lists('user_id');
                $countOfClusterHead[$stateHead->user_id]=sizeof($cluster_head_list);

                $area_sales_manager_list=DmtVendor::where('type',6)
                ->whereIn('parent_id',$cluster_head_list)
                ->lists('user_id');

                $area_sales_officer_list=DmtVendor::where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $stateHeadFTDSum[$stateHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $stateHeadLMTDSum[$stateHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $stateHeadMTDSum[$stateHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');

            }

            if(isset($stateheads[0]->message)){
                $stateheadName = '';
                $sumOfAgentAmount = '';
            }
            if(isset($stateheads[0]->name)){
                foreach ($stateheads as $statehead) {
                    $stateheadName[$statehead->id]=$statehead->name;
                }
            }

        return View::make('reports.state-head-regional-head-date-reports',['stateHeadSalesObj'=>$stateHeadSalesObj,'stateHeadSales' => $stateHeadSalesData,'countOfClusterHead'=>$countOfClusterHead,'stateHeadFTDSum'=>$stateHeadFTDSum,'stateHeadLMTDSum'=>$stateHeadLMTDSum,'stateHeadMTDSum'=>$stateHeadMTDSum, 'stateheadName'=>$stateheadName]);
        }
    }

    public function getClusterHeadRegionalHeadSalesDateReport($id){
        Paginator::setPageName('page');
        $user = Auth::user();
        if($user->vendorDetails->type == 11){

           $clusterHeadReportId = DmtVendor::where('parent_id',$id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = DmtVendor::whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = DmtVendor::whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = DmtVendor::whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = DmtVendor::whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterheads = $this->GetAepsUserNames($clusterHeadReportId);

            $clusterHeadSalesObj = DB::table('dmt_vendors')
            ->whereIn('dmt_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadSalesData=$clusterHeadSalesObj->getItems();

            //dd($areaSalesOfficerData);
            $clusterHeadFTDSum[] = 0;
            $clusterHeadLMTDSum[] = 0;
            $clusterHeadMTDSum[] = 0;
            $countOfSalesManager[] = 0;
            foreach ($clusterHeadSalesData as $clusterHead) {
                $area_sales_manager_list=Vendor::where('type',6)
                ->where('parent_id',$clusterHead->user_id)
                ->lists('user_id');
                $countOfSalesManager[$clusterHead->user_id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=DmtVendor::where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=DmtVendor::where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=DmtVendor::where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=DmtVendor::where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $clusterHeadFTDSum[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('dmt_transactions.amount');

                $clusterHeadLMTDSum[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('dmt_transactions.amount');

                $clusterHeadMTDSum[$clusterHead->user_id]=DB::table('dmt_transactions')
                ->whereIn('dmt_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('dmt_transactions.amount');

            }

            if(isset($clusterheads[0]->message)){
                $clusterheadName = '';
                $clusterHeadMTDSum = '';
                $clusterHeadLMTDSum = '';
                $clusterHeadFTDSum = '';
            }
            if(isset($clusterheads[0]->name)){
                foreach ($clusterheads as $clusterhead) {
                    $clusterheadName[$clusterhead->id]=$clusterhead->name;
                }
            }

            

        return View::make('reports.cluster-head-state-head-date-reports',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadSalesData,'countOfSalesManager'=>$countOfSalesManager,'clusterHeadFTDSum'=>$clusterHeadFTDSum,'clusterHeadLMTDSum'=>$clusterHeadLMTDSum,'clusterHeadMTDSum'=>$clusterHeadMTDSum, 'clusterheadName'=>$clusterheadName]);
        }
    }

    
}
