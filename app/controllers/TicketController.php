<?php

use Acme\Auth\Auth;
use Carbon\Carbon;
use Acme\Helper\Rabbit;
use Illuminate\Http\Request;


class TicketController extends BaseController {

  public function  createticket(){
   // dd(Input::all());
   $vendor = Vendor::where('user_id', Auth::user()->id)->first();
 $getGUID=$this->getGUID();
   $postdata=array('type'=>array('code'=>$vendor->ticket_token),'subject'=>Input::get('title'),'publicReply'=>array('body'=>Input::get('description')

   ));

 $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://lserver218-ind.megavelocity.net/api/v1/tickets",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>json_encode($postdata),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
     "token:".$vendor->ticket_token."",
    "appinstancecode:".$getGUID.""
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
dd($response);
curl_close($curl);
      

    }

 public function  getticket()
    {
       //if (! Auth::user()) return Response::json("Invalid Token", 444);
        // $postdata=array('assignee'=>array('code'=>'fssscdsds','profile'=>array('')



        // )

        // );



   $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://dipl.dev.green-earth.online/api/v1/tickets",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  //CURLOPT_POSTFIELDS =>json_encode($data),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "token:a856d9d7-ede0-4627-864e-8b99ad6a5609",
    "appinstancecode:hfdskjhf77-45o76hjds09375-98748hfjh"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
dd($response);
curl_close($curl);
      

    }


    
 public function getcategories()
{

   $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://demo.dev.green-earth.online/api/v1/categories",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  //CURLOPT_POSTFIELDS =>json_encode($data),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "token:a856d9d7-ede0-4627-864e-8b99ad6a5609",
    "appinstancecode:hfdskjhf77-45o76hjds09375-98748hfjh"

  ),
));

$response = curl_exec($curl);
$result = json_decode($response,TRUE);

$err = curl_error($curl);
curl_close($curl);
return View::make('ticket.create-ticket')
        ->with('result',$result);

      


}
public function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

public function getAepsUserDetails($id)
    {
          $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
      $body = ['user_ids' => $id];
        
      $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/v1/user-detail-for-dmtsales', $headers, $body);
    //dd($response);
      if($response->body)
      {
    //dd($response->body);
        return $response->body; 
      }else
      {
          return $id; 
      }
    //Add a comment to this line
    }
public function ticketlogin()
{

$curl = curl_init();
 //$vendor = User::where('id', Auth::user()->id)->first();
  $user = Auth::user();
//dd($user->vendorDetails);
$arraydata=array('agentId' =>$user->id,'email' =>$user->email,'firstName' =>$user->name,'lastName' =>'','mobile'=>$user->phone_no);
//dd(json_encode($arraydata));

 
$getGUID=$this->getGUID();
//dd($getGUID);
 $remove=str_replace("{",'',$getGUID);
 $removee=str_replace("}",'',$remove);
  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://support.digitalindiapayments.com/api/v1/dipl/agent",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_FOLLOWLOCATION=>true,
  CURLOPT_POSTFIELDS =>json_encode($arraydata),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "token:".$user->vendorDetails->ticket_token."",
    "appinstancecode:".$removee.""

  ),
));

$response = curl_exec($curl);
//dd($response);
$array=json_decode($response);
$err = curl_error($curl);
curl_close($curl);

if($array->errorCode == 200)
{



   $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
        

   // $body = ['ticket_token' => $array->data->token,'ticket_userId'=>$array->data->userId];
        
   // $response = \Unirest\Request::POST(getenv('AUTH_URL').'/api/auth/v1/updatetokencode', $headers, $body);


   // $vendor = Vendor::where('user_id', Auth::user()->id)->first();
   // $vendor->ticket_token=$array->data->token;
   // $vendor->ticket_userId=$array->data->userId;
   // $vendor->appinstancecode=$removee;
   // $vendor->save();
//setcookie('at_token', $array->data->token, time() + (86400 * 30));
    //$dffff=Auth::cokkies($array->data->token,$getGUID);
//setcookie('at_appCode', $removee, time() + (86400 * 30));
  
     
  return Response::json([
      // @todo confirm with amol
      "at_token" => $array->data->token,
      "at_appCode" =>$removee], 200);
   
}else
{
$msg='Something Went Wrong..!';
return Response::json($msg, 422);

}


}

                  /********** orggen ticket working start here *************/
  public function getAddTicket()
  {
    if (! Auth::user()) return Redirect::to('/');
    return View::make('ticket.add-ticket');
  } 

  public function postGenerateTicket()
  {
    if (! Auth::user()) return Redirect::to('/');

    $customer_code = Auth::user()->id;
    $created_by = 1;      /*created_by can be 1-customer,2-technician (Note : technician_phone is Required only if created_by value is 2)*/
    $api_key = "dipl689ssslodjemkews";   

    $headers = array('Accept' => 'application/json');

    $post_data = array(
      array(
      'customer_data' => array(
        'customer_code' => $customer_code
      ),
      'ticket_data' => array(
        'priority' => Input::get('priority'),
        'product' => Input::get('product'),
        'comment' => Input::get('comment'),
        'issue1' => Input::get('issue1'),
        'issue2' => Input::get('issue2'),
        'created_by' => $created_by
      ),
        'api_key' => $api_key
      )
    );

    $data = json_encode($post_data);
    $data1 = array('data' => $data);

    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/customer_api/add_ticket', $headers, $body);

    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/ticket-debug.log');
    Log::info(['Request'=>$body]);
    Log::info(['Response'=>$response->body]);

    $api_response = json_encode($response->body);
    $arr = json_decode($api_response,true);
    $msg = $arr[0]['response'];
    if($msg == 'success')
      return Response::json($msg, 200);
    else
      return Response::json($arr[0]['msg'], 422);
  }

  public function postImportCustomer()
  {
    $api_key = "dipl689ssslodjemkews";

    /* for current date
    $result = DB::table('user_vendors')
                ->join('users','users.id','=','user_vendors.user_id')
                ->join('cities','cities.id','=','user_vendors.city')
                ->join('state','state.id','=','user_vendors.state')
                ->select('users.id','users.name','users.email','users.phone_no','cities.name As city','state.state_name As state','users.created_at')
                ->whereRaw('date(users.created_at) = ?', [Carbon::today()])
                ->get();*/

    /* with city state country
    $result = DB::table('user_vendors')
              ->join('users','users.id','=','user_vendors.user_id')
              ->join('cities','cities.id','=','user_vendors.city')
              ->join('state','state.id','=','user_vendors.state')
              ->select('users.id','users.name','users.email','users.phone_no','cities.name As city','state.state_name As state','users.created_at')
              ->orderBy('users.id', 'asc')
              ->limit(10)
              ->get();*/

      // without address city state country
      $prev_date = date('Y-m-d',strtotime("-1 days"));

      $result = DB::table('user_vendors')
                ->join('users','users.id','=','user_vendors.user_id')
                ->select('users.id','users.name','users.email','users.phone_no','users.created_at')
                ->orderBy('users.id', 'asc')
                ->whereRaw('date(users.created_at) = "'.$prev_date.'"')
                ->get();

        /*$result = DB::table('user_vendors')
                ->join('users','users.id','=','user_vendors.user_id')
                ->select('users.id','users.name','users.email','users.phone_no','users.created_at')
                ->where('users.id',311)
                ->orWhere('users.id',5279)
                ->get();*/

      // print_r($result);
      // exit;

      $count = 1;

      $post_data = array();
      /* example with dummy data
      $post_data[$count] = array(
        'api_key' => $api_key,
        'name' => "Sadhana",
        'customer_code' => "DIG-TEST-001",
        'email' => array("sadhana.nikam@digitalindiapayments.com"),
        'phone' => array(9664021505),
        'address' => array(array('city' => "Mumbai" , 'state' => "Maharashtra" , 'country' => 'India')),
        'Registration Date' => "15-12-2017"
        );*/
      /*with city state country
      foreach($result as $r){
        $post_data[$count] = array(
          'api_key' => $api_key,
          'name' => $r->name,
          'customer_code' => "DIG-".$r->id,
          'email' => array($r->email),
          'phone' => array($r->phone_no),
          'address' => array(array('city' => $r->city , 'state' => $r->state , 'country' => 'India')),
          'Registration Date' => date('d-m-Y' , strtotime($r->created_at))
          );
        $count++;
      }*/

      foreach($result as $r){
        $post_data[$count] = array(
          'api_key' => $api_key,
          'name' => $r->name,
          'customer_code' => $r->id,
          'email' => array(rtrim($r->email,'/')),
          'phone' => array($r->phone_no),
          // 'address' => array(array('city' => $r->city , 'state' => $r->state , 'country' => 'India')),
          'Registration Date' => date('d-m-Y' , strtotime($r->created_at))
          );
        $count++;
      }
      // print_r(json_encode($post_data));
      // exit;

      $headers = array('Accept' => 'application/json');
      $data = json_encode($post_data);

      $data1 = array('data' => $data);
      $body = Unirest\Request\Body::form($data1);
      $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/customer_api/add_customer', $headers, $body);
      $api_response = json_encode($response->body);

      // log something to storage/logs/debug.log
      Log::useFiles(storage_path().'/logs/ticket-debug.log');
      Log::info(['Request'=>$body]);
      Log::info(['Response'=>$response->body]);
      
      return Response::json($api_response, $response->code);
      // return Response::json('Done', 200);

      // print_r($api_response);
  }

  public function getAllTicket()
  {
    if (! Auth::user()) return Redirect::to('/');

    $headers = array('Accept' => 'application/json');
    $api_key = "dipl689ssslodjemkews";
    $customer_code = Auth::user()->id;
    $data1 = array('api_key' => $api_key,'customer_code' => $customer_code);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/ticket_api/get_ticket_details', $headers, $body);
    $tickets = $response->body;
    
    $arr1 = $tickets->CustomerList[0]->TicketList;

    $post_data = $arr1;

    // $ticketsObj = Paginator::make($post_data, count($post_data), 3);

    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/ticket-debug.log');
    Log::info(['Request'=>$body]);
    Log::info(['Response'=>$response->body]);

    return View::make('ticket.all-ticket',['tickets' => json_encode($post_data)]);
    // return View::make('ticket.all-ticket',['tickets' => json_encode($post_data), 'ticketsObj' => $ticketsObj]);
    // return View::make('ticket.all-ticket',['tickets' => $ticketsObj->getItems(), 'ticketsObj' => $ticketsObj]);
  } 

  public function getTicketDetail ($id)
  {
    if (! Auth::user()) return Redirect::to('/');

    /*get comments of ticket no.*/
    $headers = array('Accept' => 'application/json');
    $api_key = "dipl689ssslodjemkews";
    $ticket_no = $id;

    /*get all details of ticket*/
    $data1 = array('api_key' => $api_key,'ticket_no' => $ticket_no);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/ticket_api/get_all_ticket', $headers, $body);
    $resp = $response->body;
    $post_data = $resp->TicketDetail;

    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/ticket-debug.log');
    Log::info(['Request'=>$body]);
    Log::info(['Response'=>$response->body]);

    return View::make('ticket.view-ticket')->with('ticket_data', json_encode($post_data))->with('ticket_no', $ticket_no);
  } 

  public function postInsertComment()
  {
    if (! Auth::user()) return Redirect::to('/');

    $api_key = "dipl689ssslodjemkews";
    $ticket_no = Input::get('ticket_no');

    $headers = array('Accept' => 'application/json');

    $post_data = array(
      'api_key' => $api_key,
      'ticket_no' => Input::get('ticket_no'),
      'comment' => Input::get('comment'),
      'comment_owner' => array(
        'owner_type' => 3,
        'owner_name' => Auth::user()->name,
        'owner_phone' => Auth::user()->phone_no
      )
    );

    $data = json_encode($post_data);
    // print_r($data);
    // exit;
    $data1 = array('data' => $data);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/ticket_api/insert_comment', $headers, $body);
    // $api_response = json_encode($response->body);
    // print_r($response);
    // return Response::json($api_response, $response->code);

    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/ticket-debug.log');
    Log::info(['Request'=>$body]);
    Log::info(['Response'=>$response->body]);

    return Response::json('success', 200);
  } 

  public function getTicketCurrentStatus()
  {
    $headers = array('Accept' => 'application/json');
    $api_key = "dipl689ssslodjemkews";
    $ticket_no = "RN1805180012";
    $data1 = array('api_key' => $api_key,'ticket_no' => $ticket_no);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/ticket_api/ticket_status', $headers, $body);
    $comments = json_encode($response->body);
    return Response::json($comments, $response->code);
  }

  public function getTicketStatusHistory()
  {
    $headers = array('Accept' => 'application/json');
    $api_key = "dipl689ssslodjemkews";
    $ticket_no = "RN1805180012";
    $data1 = array('api_key' => $api_key,'ticket_no' => $ticket_no);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/ticket_api/get_status_history', $headers, $body);
    $comments = json_encode($response->body);
    return Response::json($comments, $response->code);
  }

  public function getProductIssueList($product_name)
  {
    $headers = array('Accept' => 'application/json');
    $api_key = "dipl689ssslodjemkews";
    $data1 = array('api_key' => $api_key,'product_name' => $product_name);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/product_api/get_issues', $headers, $body);
    $issues = $response->body;
    return Response::json($issues, $response->code);
  }

  public function ajaxAllTickets()
  {
    if (! Auth::user()) return Redirect::to('/');

    $headers = array('Accept' => 'application/json');
    $api_key = "dipl689ssslodjemkews";
    $customer_code = Auth::user()->id;
    $data1 = array('api_key' => $api_key,'customer_code' => $customer_code);
    $body = Unirest\Request\Body::form($data1);
    $response = Unirest\Request::post('https://diplcare.ogcrm.com/api/ticket_api/get_ticket_details', $headers, $body);
    $tickets = $response->body;
    
    $arr1 = $tickets->CustomerList[0]->TicketList;

    $data = $arr1;
    print_r(json_encode($data));

    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/ticket-debug.log');
    Log::info(['Request'=>$body]);
    Log::info(['Response'=>$response->body]);

    // print_r(json_encode($data));

    // $data = DB::table('users')
    //         ->select('name','id');

    return Datatables::of($data)->make(true);
  } 
}  