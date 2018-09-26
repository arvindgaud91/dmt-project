<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;

/**
*
*/
class PagesController extends HomeController
{
	function __construct()
	{

	}

	public function getMyDistributorsPage ()
	{
		
		if (! Auth::user()) return Redirect::to('/');
		$vendorDetails = Auth::user()->vendorDetails;
		
		if (! $vendorDetails || $vendorDetails->type != 3) return Redirect::to('/');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/children', $headers);
		$agents = $response->body;

		//dd(json_encode($agents));
		$dmtVendorBalance;

		$vendor_data = new Vendor;
        $vendor_data->setConnection('mysql2');
		
		foreach ($agents as $balance) {
			//dd($balance->vendor->user_id);
			$dmtVendorBalance[$balance->vendor->user_id]=$vendor_data->where('user_id',$balance->vendor->user_id)->pluck('balance');
		}
			//dd(json_encode($dmtVendorBalance));
		return View::make('pages.my-distributors',['dmtVendorBalance'=>$dmtVendorBalance])
			->withAgents($agents);
	}

	public function getDistributorAgents ($distributorId)
	{
		GateKeeper::checkRoles(Auth::user(), 3);

		$vendor_data = new Vendor;
        $vendor_data->setConnection('mysql2');

		if (! Auth::user()) return Redirect::to('/');
		$vendorDetails = $vendor_data->where('user_id', Auth::user()->id)->first();
		if (! $vendorDetails || $vendorDetails->type != 3) return Redirect::to('/');

		//dd(Auth::user()->last_login());

		$distributor = $vendor_data->where('user_id', $distributorId)->where('parent_id', Auth::user()->id)->first();
		if (! $distributor) return Redirect::to('/');

		$ids = $vendor_data->where('parent_id', $distributorId)
			->where('type', 1)
			->lists('user_id');
		$agents = User::whereIn('id', $ids)->get();
		return View::make('pages.distributor-agents')
			->withAgents($agents)
			->withDistributor($distributor->user);
	}

	public function getMyAgentsPage ()
	{

		$current_page=Input::get('page') ? Input::get('page') : "1";
		if (! Auth::user()) return Redirect::to('/');
		
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	    $data = [
	       'user_id'=> (string)\Cookie::get('userid'),
	       'user_type'=>'1',
		 	"currentpage"=>$current_page,
		 	"per_page"=>"100"
	   	];

		$body = Unirest\Request\Body::json($data);


		//$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/viewuserreportrequest', $headers, $body);
		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/userreport', $headers, $body);
		// dd($response);
		// $errorrem= new ErrorRmitter();
	 //    $errorrem->request=json_encode($data);
	 //    $errorrem->response=json_encode($response);
	 //    $errorrem->save();

		if($response->code == 200)
		{   
			$record=1;
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
          	}
          	else{
          		$request_data[]='';
          		$record=0;
        	}
//dd($request_data);
			return View::make('pages.my-agents',['requestList'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record]);
	    }
		
	}
}
