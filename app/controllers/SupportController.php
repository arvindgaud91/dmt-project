<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
//use App\Models\Support;

/**
*
*/
class SupportController extends HomeController
{
	function __construct()
	{
    
   
	}

	public function add_support ()
	{
		$ticket_id=Input::get('ticket_id');
    $type=Input::get('type');
    $message=Input::get('message');
    
    
    $validator = Validator::make(array(
      'ticket_id'=>$ticket_id,
      'type'=>$type,
      'message'=>$message,
      'status'=>'Open'
    ), array(
      'ticket_id' => 'required',
      'type' => 'required',
      'message' => 'required'
      
    ));
    if ($validator->fails())
      return Response::json($validator->messages(), 500);
    $support_data=array(
      'ticket_id'=>$ticket_id,
      'type'=>$type,
      'user_id'=>Auth::user()->id,
      'message'=>$message,
      'status'=>'Open',
      'created_at'=>date('Y-m-d H:i:s'),
    );
    $support = new Support();
    $support->insert($support_data);
      //return $balanceRequest;
  }
  public function support_report ()
  {
   
    Paginator::setPageName('pag');
    $data = Support::join('master_support', 'support.type', '=', 'master_support.support_id') ->select('support.*', 'master_support.support_name')->orderBy('id', 'desc')->where('status','Open')->where('user_id',Auth::user()->id)->paginate(100);
    
    return View::make('support.support_report', ['support_data' => $data,'select_data'=>$data]);
             //return View::make('support.support_report');
  }
  
  public function support_all_data ()
  {
    
   Paginator::setPageName('page');
   $data = Support::join('master_support', 'support.type', '=', 'master_support.support_id') ->select('support.*', 'master_support.support_name')->orderBy('id', 'desc')->paginate(100);
   
   return Response::json($data, 200);
 }
 public function support_response_submit ()
 {
   
  $tracker = Request::header('auth');
  $headers = [
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
    'auth' => \Cookie::get('tracker')
  ];
  $body = \Unirest\Request\Body::json(['token' => $tracker]);
  $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
  if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

    $support_data = $this->filterOnly(Input::all(), ['response', 'status', 'response_date', 'ticket_id']);
    
    $support=Support::where('ticket_id',$support_data['ticket_id'])->update($support_data);
    return Response::json($support, 200);
    
  }

  
}
