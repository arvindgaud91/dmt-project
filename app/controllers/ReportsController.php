<?php
use Acme\Auth\Auth;
use Acme\Helper\Rabbit;
use Acme\Helper\GateKeeper;
use Carbon\Carbon;
use Acme\Helper\Export;

class ReportsController extends BaseController
{
	  public function getRequestReport()
	  {
	 
      $current_page=Input::get('page') ? Input::get('page') : "1";
      /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"WR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
            "from_date"=>"",
          "to_date"=>"",
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      //    $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      //dd($response);
    /*ENd web services*/


    if($response->code == 200)
    {     $record=1;
          $per_page=0;
          $total=0;
          $current_page=0;
         if(isset($response->body->requestvalues)){

            if(isset($response->raw_body)){
            
            

          $response_data=json_decode($response->raw_body);
          $per_page=$response_data->per_page;
          $total=$response_data->total;
          $current_page=$response_data->current_page;

          foreach ($response_data->requestvalues as $requests) {

            $request_data[] = json_decode($requests);
          }
     

            }else{
              $request_data='';
               $record=0;
            }
        }else{
           $request_data[]='';
           $record=0;
        }

      return View::make('reports.request-reports',['requests'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=> $record]);
      }



	}




  public function getRequestReportdaywise()
    {
   
      $current_page=Input::get('page') ? Input::get('page') : "1";
      /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"WR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
           "from_date"=>Input::get('from_date'),
          "to_date"=>Input::get('to_date'),
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      //    $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      //dd($response);
    /*ENd web services*/


    if($response->code == 200)
    {     $record=1;
          $per_page=0;
          $total=0;
          $current_page=0;
         if(isset($response->body->requestvalues)){

            if(isset($response->raw_body)){
            
            

          $response_data=json_decode($response->raw_body);
          $per_page=$response_data->per_page;
          $total=$response_data->total;
          $current_page=$response_data->current_page;

          foreach ($response_data->requestvalues as $requests) {

            $request_data[] = json_decode($requests);
          }
     

            }else{
              $request_data='';
               $record=0;
            }
        }else{
           $request_data[]='';
           $record=0;
        }

      return View::make('reports.request-reports',['requests'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=> $record]);
      }



  }


  public function getRequestReportdaywiseexport()
    {
   
      $current_page=Input::get('page') ? Input::get('page') : "1";
      /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"WR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
           "from_date"=>Input::get('from_date'),
          "to_date"=>Input::get('to_date'),
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      //    $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      //dd($response);
    /*ENd web services*/


    if($response->code == 200)
    {     $record=1;
          $per_page=0;
          $total=0;
          $current_page=0;
         if(isset($response->body->requestvalues)){

            if(isset($response->raw_body)){
            
            

          $response_data=json_decode($response->raw_body);
          $per_page=$response_data->per_page;
          $total=$response_data->total;
          $current_page=$response_data->current_page;

          foreach ($response_data->requestvalues as $requests) {

            $request_data[] = json_decode($requests);
          }
     

            }else{
              $request_data='';
               $record=0;
            }
        }else{
           $request_data[]='';
           $record=0;
        }
  $export_csv= new Export();
      $export_csv->exportData(json_decode( json_encode($request_data), true),"wallet-Request-");
      //return View::make('reports.request-reports',['requests'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=> $record]);
      }



  }
	public function getRequestExport(){

		$dmt_vendor_data = new DmtVendor;
        $dmt_vendor_data->setConnection('mysql2');

        $wallet_balance_request_data = new WalletBalanceRequest;
        $wallet_balance_request_data->setConnection('mysql2');

		$dmt_vendor=$dmt_vendor_data->where('user_id', Auth::user()->id)->first();
           $user = Auth::user();
           if($dmt_vendor->type==1 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
                  $records = $wallet_balance_request_data->whereBetween('wallet_balance_requests.created_at', [$start_date, $end_date])->where('wallet_balance_requests.user_id', Auth::user()->id)
                  ->select('wallet_balance_requests.id as ReqNo', 
							'wallet_balance_requests.created_at as Date',
							'wallet_balance_requests.amount as Amount',
					'wallet_balance_requests.branch as Branch',
							
					DB::raw("(if((wallet_balance_requests.bank=7), 'ICICI BANK', '')) as Bank,
					(CASE WHEN wallet_balance_requests.transfer_mode=1 THEN 'IMPS'
					WHEN wallet_balance_requests.transfer_mode=2 THEN 'NEFT'
					WHEN wallet_balance_requests.transfer_mode=3 THEN 'Cash Deposit To Bank'
					WHEN wallet_balance_requests.transfer_mode=4 THEN 'RTGS'
					END) as Mode"),
					'wallet_balance_requests.reference_number as RefNo',DB::raw("
					(CASE WHEN wallet_balance_requests.status=0 THEN 'Pending' WHEN wallet_balance_requests.status=1 THEN 'Accepted' WHEN wallet_balance_requests.status=2 THEN 'Rejected'END) as Status"))->orderBy('wallet_balance_requests.id', 'DESC')->get()->toArray();
                    }    
              }
              elseif($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
                  $records = $wallet_balance_request_data->whereBetween('wallet_balance_requests.created_at', [$start_date, $end_date])->where('wallet_balance_requests.user_id', Auth::user()->id)
                  ->select('wallet_balance_requests.id as ReqNo', 
							'wallet_balance_requests.created_at as Date',
							'wallet_balance_requests.amount as Amount',
					'wallet_balance_requests.branch as Branch',
							
					DB::raw("(if((wallet_balance_requests.bank=7), 'ICICI BANK', '')) as Bank,
					(CASE WHEN wallet_balance_requests.transfer_mode=1 THEN 'IMPS'
					WHEN wallet_balance_requests.transfer_mode=2 THEN 'NEFT'
					WHEN wallet_balance_requests.transfer_mode=3 THEN 'Cash Deposit To Bank'
					WHEN wallet_balance_requests.transfer_mode=4 THEN 'RTGS'
					END) as Mode"),
					'wallet_balance_requests.reference_number as RefNo',DB::raw("
					(CASE WHEN wallet_balance_requests.status=0 THEN 'Pending' WHEN wallet_balance_requests.status=1 THEN 'Accepted' WHEN wallet_balance_requests.status=2 THEN 'Rejected'END) as Status"))->orderBy('wallet_balance_requests.id', 'DESC')->get()->toArray();
                    }    
              }    
        $export_csv= new Export();
        $export_csv->exportData($records,"request-report-");
    }

}
