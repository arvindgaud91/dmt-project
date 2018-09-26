<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with remitter APIs
*/
class KycController extends BaseController
{
	function __construct()
	{
		
	}

	public function postKycForm()
	{
		
         if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    		$user = Auth::user();
    		if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 

    			return Response::json(['message' => 'Unauthorized access'], 401);


		                $destinationPath = 'public/upload/kyc';
                        $file=Input::file('file');
                        $kyc_pan_card = Input::get('sender_id') . '_id_proof.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath . '/', $kyc_pan_card);

                         $destinationPath = 'public/upload/kyc';
                        $file=Input::file('file1');
                        $kyc_add_proof = Input::get('sender_id') . '_add_proof.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath . '/', $kyc_add_proof);

                        $destinationPath = 'public/upload/kyc';
                        $file=Input::file('file2');
                        $kyc_req_form = Input::get('sender_id') . '_Remitter_Reg_Form.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath . '/', $kyc_req_form);


                            	$kyc = new RemitterKycdetails;
                                $kyc->remitter_id = Input::get('sender_id');
                                $kyc->last_name = Input::get('lname');
                                $kyc->mother_name = Input::get('mother_name');
                                $kyc->father_name = Input::get('father_name');
                                $kyc->email_id = Input::get('email');
                                $kyc->dob = Input::get('dob');
                                $kyc->gender=Input::get('gender');
                                $kyc->education=Input::get('education');
                                $kyc->religion=Input::get('religion');
                                $kyc->nationality=Input::get('nationality');
                                $kyc->category=Input::get('category');
                                $kyc->marital_status=Input::get('marital_status');
                                $kyc->residential_status=Input::get('residential_status');
                                $kyc->pan_no=Input::get('pan');
                                $kyc->add_proof_type=Input::get('selectaddress_proof');
                                $kyc->nominee_name=Input::get('nominee_name');
                                $kyc->relation_nominee=Input::get('nominee_relation');
                                $kyc->age_nominee=Input::get('aon');
                                $kyc->dob_nominee=Input::get('dob_nominee');
                                $kyc->cust_status=Input::get('customer_status');
                                $kyc->cust_type=Input::get('customer_type');
                                $kyc->income_type=Input::get('income_type');
                                $kyc->annual_income=Input::get('annual_income');
                                $kyc->politically_exposed=Input::get('politically_exposed');
                                $kyc->kyc_pan_card=$kyc_pan_card;
                                $kyc->kyc_add_proof=$kyc_add_proof;
                                $kyc->kyc_req_form=$kyc_req_form;
                                $kyc->save();



                                $kycadd=new Kyc();
                                $kycadd->remitter_id=Input::get('sender_id');
                                $kycadd->mobile_number=Input::get('mobile');
                                $kycadd->consumedLimit=0.000;
                                $kycadd->remainingLimit=25000;
                                $kycadd->kycStatus=0;
                                $kycadd->save();

                                if($kyc)
                                {

                                  return Response::json(['message' => 'KYC Form Submitted Successfully'], 200);

                                }else
                                {
                                return Response::json(['message' => 'KYC Form Submitted UnSuccessfully'], 400);
                                }

	}


    public function getkycdetails()
    {
     
        if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
        $user = Auth::user();
        if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
            return Response::json(['message' => 'Unauthorized access'], 401);
        $RemitterKycdetails= RemitterKycdetails::get();
        

        return View::make('sender.kyc-details', array('data' => $RemitterKycdetails));

    }




    public function updateuploaded($kycid)
    {
        if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
        $user = Auth::user();
        if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
            return Response::json(['message' => 'Unauthorized access'], 401);
        $RemitterKycdetails= RemitterKycdetails::find($kycid);
        //dd($RemitterKycdetails);
        if($RemitterKycdetails)
        {
            $RemitterKycdetails->isUploaded=1;
            $RemitterKycdetails->save();

         return Redirect::back()->with('message','Documents Uploaded Successfully');
              



        }else
        {
            return Redirect::back()->with('error','Documents  Not Uploaded');
          
        }
    


    }


function bank_api_call($URL,$xml,$xml_file) 
{
    
    $ch = curl_init($URL);
    $fp = fopen("$xml_file", "w");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
    //Write the API content in xml file

    $output = curl_exec($ch);
    fwrite($fp, $output);

    curl_close($ch);
    fclose($fp);
    return $output;
                
}


    public function getRemitterDetails($remitterid)
    { 


    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
    if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
    return Response::json(['message' => 'Unauthorized access'], 401);

   

    $Remitterdetails = DB::table('remitters')
            ->join('kyc', 'kyc.remitter_id', '=', 'remitters.rbl_remitter_code')
            ->where('kyc.remitter_id','=',$remitterid)
            ->first();
       


        if($Remitterdetails->id)
        { 
            
            $agent_id = $Remitterdetails->user_id;
            $curr_kyc_status = $Remitterdetails->kycStatus;

            $log = " Remitter Details - Agent :: $agent_id Current KYC status :: $curr_kyc_status \n";
            $session = Session::all();
            $session_token=$session['_token'];
            $doc_dir="public/upload/kyc";
            $rbl_uat_url = "https://10.80.45.46:1000/BCAPI/Apiservices.aspx";
             if($curr_kyc_status != '1') 
            {
                     $xml_data =
                            "<channelpartnerloginreq>
                                <username>HariPrasath</username>
                                <password>FD91D4C2CDEBD9C6AA547978672D06826441EF46</password>
                                <bcagent>".$Remitterdetails->name."</bcagent>
                             </channelpartnerloginreq>";
                    
                    
                    $output = $this->bank_api_call($rbl_uat_url, $xml_data, $session_token);

                    dd($output);

                    $rbltoken=new rbltoken();
                    $rbltoken->user_name=$Remitterdetails->name;
                    $rbltoken->user_id=$agent_id;
                    $rbltoken->sessiontoken=$session_token;
                    $rbltoken->status=1;
                    $rbltoken->save();

                     $arr_files = glob($doc_dir."/*$remitterid*");
                     //dd($arr_files);
                if(!empty($arr_files)) 
                {
                    foreach($arr_files as $file) 
                    {
                        $file_name = basename($file);
                        if(strpos($file_name,'id') !== FALSE)
                        {
                            $id_proof_url = $file_name;
                        } else if(strpos($file_name,'add') !== FALSE) {
                            $add_proof_url = $file_name;
                        } else if(strpos($file_name,'Reg') !== FALSE) {
                            $reg_form_url = $file_name;
                        }
                    }

                //dd($reg_form_url);
                $log = "Document Details -  $id_proof_url , $add_proof_url , $reg_form_url ";
                if(file_exists($doc_dir."/".$id_proof_url) && file_exists($doc_dir."/".$add_proof_url)) 
                {


                    // get remitter kyc details
                    $GetRemitterKycdetails = RemitterKycdetails::first();
                   // dd($GetRemitterKycdetails);
                    
                    // call kyc upload api
                    $remitter_kyc_xml = "<remitterkycreq>
                                        <header>
                                        <sessiontoken>".$session_token."</sessiontoken>
                                        </header>
                                        <remitterid>".$remitterid."</remitterid>
                                        <bcagentid></bcagentid>
                                        <idproofname>Pancard</idproofname>
                                        <idproofnumber>".$GetRemitterKycdetails->pan_no."</idproofnumber>
                                        <idproofissuedate></idproofissuedate>
                                        <idproofexpirydate></idproofexpirydate>
                                        <idproofissueplace></idproofissueplace>
                                        <addressproof>".$GetRemitterKycdetails->add_proof_type."</addressproof>
                                        <idproof>Pancard</idproof>
                                        <addressproofurl>".$add_proof_url."</addressproofurl>
                                        <idproofurl>".$id_proof_url."</idproofurl>
                                        <CustomerStatus>".$GetRemitterKycdetails->cust_status."/CustomerStatus>
                                        <CustomerType>".$GetRemitterKycdetails->cust_type."</CustomerType>
                                        <SourceIncomeType>".$GetRemitterKycdetails->income_type."</SourceIncomeType>
                                        <AnnualIncome>".$GetRemitterKycdetails->annual_income."</AnnualIncome>
                                        <PoliticallyExposedPerson>".$GetRemitterKycdetails->politically_exposed."</PoliticallyExposedPerson>
                                        <uploadtype>1</uploadtype>
                                        </remitterkycreq>
                                        ";
                    // call bank API
                    echo "upload kyc xml \n";
                    echo $remitter_kyc_xml;
                    
                    $upload_xml_file = "public/xmlfiles/uploadKyc.xml";
                    $output = $this->bank_api_call($rbl_uat_url, $remitter_kyc_xml, $upload_xml_file);

                    $log .= " KYC Upload Api Response :: $output \n";
                    //Change the file header from UTF-16 to UTF-8
                    $fname = $upload_xml_file;
                    $fhandle = fopen($fname,"r");
                    $content = fread($fhandle,filesize($fname));
                    $content = str_replace("utf-16", "utf-8", $content);
                    $fhandle = fopen($fname,"w");
                    fwrite($fhandle,$content);
                    fclose($fhandle); 

                    $uploadkycxml=simplexml_load_file($upload_xml_file);                    
                    $upload_kyc_status = isset($uploadkycxml->status) ? $uploadkycxml->status : 0;
                    echo "\n upload kyc response :: \n";
                    echo $output;
                    if(isset($uploadkycxml->status) && $uploadkycxml->status == 1) {
                        // call upload registration form api
                        
                        $reg_form_xml = "<remitterkycregformreq>
                                        <header>
                                        <sessiontoken>".$session_token."</sessiontoken>
                                        </header>
                                        <remitterid>".$remitterid."</remitterid>
                                        <bcagentid>".$Remitterdetails->name."</bcagentid>
                                        <registrationform>".$reg_form_url."</registrationform>
                                        <uploadtype>1</uploadtype>
                                        </remitterkycregformreq>                                
                                        ";
                        echo " reg form xml \n";
                        echo $reg_form_xml;
                        
                        // call bank API
                        $regform_xml_file = "xmlfiles/regFormRemitter.xml";
                        $output =  $this->bank_api_call($rbl_uat_url, $reg_form_xml, $regform_xml_file);

                        $log .= " Registration Form Upload Api Response :: $output \n"; 
                        
                        //Change the file header from UTF-16 to UTF-8
                        $fname = $regform_xml_file;
                        $fhandle = fopen($fname,"r");
                        $content = fread($fhandle,filesize($fname));
                        $content = str_replace("utf-16", "utf-8", $content);
                        $fhandle = fopen($fname,"w");
                        fwrite($fhandle,$content);
                        fclose($fhandle); 

                        $regformxml=simplexml_load_file($regform_xml_file);
                        $regformxml->status;
                        
                        echo 'KYC Upload API Call Successful';
                    } else {
                        // error in upload kyc 
                        echo 'Upload Api call fails';
                    }

                    }
                     else {
                        echo 'kyc douments not found';
                    }   
                     









                }else
                {
                    echo "Documents not found on server.";

                }

            }else
            {
               $log .= "Already KYC remitter \n";
                echo 'Already KYC remitter';
             
            }
                    
            }

            
           



    
     

    }

}