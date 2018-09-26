	<?php
use Acme\Auth\Auth;
use Carbon\Carbon;

class HomeController extends BaseController {

	public function __construct ()
	{
		$this->beforeFilter('auth', [
			'only' => ['index']
		]);
		$this->beforeFilter('guest', [
			'only' => ['landing']
		]);
		
	}

	public function index ()
	{
		//dd(Auth::user()->vendorDetails->type);

		/*Web services*/

			$headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'userid' => \Cookie::get('userid'),
		      'parent_userid'=>\Cookie::get('parentid'),
		      'user_type'=>\Cookie::get('user_type')
		       ];
		    $body = Unirest\Request\Body::json($data);
		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/DashboardNew', $headers, $body);
		 //dd($response->raw_body);
		/*End web services*/
            
		// $errorrem= new ErrorRmitter();
	 //    $errorrem->request=json_encode($data);
	 //    $errorrem->response=json_encode($response);
	 //    $errorrem->save();

if($response->code == 200)
{
		$p=json_decode($response->raw_body);


		 if (\Cookie::get('user_type') == 1) {
		 	$data['transactions_monthly'] =$p->monthly_txn_amount ? $p->monthly_txn_amount:'0'; 
		 	$data['senders_monthly'] =0;//$p->montly_remitter_count ? $p->montly_remitter_count:'0'; 
		 	$data['total_transactions'] =$p->overall_txn_amt ? $p->overall_txn_amt:'0'; 
		 	$data['total_senders'] =$p->overall_remitter_count ? $p->overall_remitter_count:'0';
		 	$data['monthlyrefund_txn_amt'] =$p->monthlyrefund_txn_amt ? $p->monthlyrefund_txn_amt:'0';
		 	$data['overallrefund_txn_amt'] =$p->overallrefund_txn_amt ? $p->overallrefund_txn_amt:'0'; 
		 	
		 	return View::make('home.index-agent')->with($data);
			}

		 if (\Cookie::get('user_type') == 2){
		 	$data['child_balance'] =   $p->overall_remitter_balance ? $p->overall_remitter_balance:'0';
		 	$data['child_count'] =$p->overall_tot_count ? $p->overall_tot_count:'0';
		 	return View::make('home.index-distributor')->with($data);
		 }
		 
		 
		 $data['agentBalance'] = 0;
		 
		if (\Cookie::get('user_type') == 3) 
		 	return View::make('home.index-super-distributor')->with($data);

		if(\Cookie::get('user_type') == 4){
			return View::make('home.index-sale');
		}
		if(\Cookie::get('user_type') == 5){
        	return View::make('home.index-area-sales-officer');
        }
        if(\Cookie::get('user_type') == 6){
        	return View::make('home.index-area-sales-manager');
        }
        if(\Cookie::get('user_type') == 7){
        	return View::make('home.index-cluster-head');
        }
        if(\Cookie::get('user_type') == 10){
        	return View::make('home.index-state-head');
        }
        if(\Cookie::get('user_type') == 11){
        	return View::make('home.index-regional-head');
        } 

  }else
  {
	//$p=json_decode($response->raw_body);


		 if (\Cookie::get('user_type') == 1) {
		 	$data['transactions_monthly'] =0; 
		 	$data['senders_monthly'] =0; 
		 	$data['total_transactions'] =0; 
		 	$data['total_senders'] =0;
		 	$data['monthlyrefund_txn_amt'] =0;
		 	$data['overallrefund_txn_amt'] =0; 
		 	
		 	return View::make('home.index-agent')->with($data);
			}

		 if (\Cookie::get('user_type') == 2){
		 	$data['child_balance'] =   0;
		 	$data['child_count'] =0;
		 	return View::make('home.index-distributor')->with($data);
		 }
		 
		
		 $data['agentCount'] = 0;

		if (\Cookie::get('user_type') == 3) 
		 	return View::make('home.index-super-distributor')->with($data);

		if(\Cookie::get('user_type') == 4){
			return View::make('home.index-sale');
		}
		if(\Cookie::get('user_type') == 5){
        	return View::make('home.index-area-sales-officer');
        }
        if(\Cookie::get('user_type') == 6){
        	return View::make('home.index-area-sales-manager');
        }
        if(\Cookie::get('user_type') == 7){
        	return View::make('home.index-cluster-head');
        }
        if(\Cookie::get('user_type') == 10){
        	return View::make('home.index-state-head');
        }
        if(\Cookie::get('user_type') == 11){
        	return View::make('home.index-regional-head');
        } 

  }

}



		
	public function landing ()
	{
		return View::make('landing');
	}


}
