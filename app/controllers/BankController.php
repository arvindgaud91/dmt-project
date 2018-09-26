<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;

/**
*
*/
class BankController extends HomeController
{
	function __construct()
	{

	}

	public function addBank()
	{
      
     $DmtBanktable = new DmtBank();
     $DmtBanktable->name=Input::get('bank_name');
     $DmtBanktable->save();
      if($DmtBanktable)
      {
      return Response::json(['success'=>'success'], 200);
      }else
      {
      return Response::json(['error'=>'error'], 400);
      }

	   
	  


	}

	public function bankbranchAdd()
	{

		//dd(Input::all());
		$branchid=Input::get('bank_ID');
      $ifsc=Input::get('ifsc');
      $branch_name=Input::get('branch_name');
      $address=Input::get('address');
      $city=Input::get('city');
      $state=Input::get('state');
    

		$DmtBankBranch = new DmtBankBranch();
		$DmtBankBranch->dmt_bank_id=$branchid;
		$DmtBankBranch->ifsc=$ifsc;
		$DmtBankBranch->branch=$branch_name;
		$DmtBankBranch->address=$address;
		$DmtBankBranch->city=$city;
		$DmtBankBranch->state=$state;
		$DmtBankBranch->save();

		if($DmtBankBranch)
      {
      return Response::json(['success'=>'success'], 200);
      }else
      {
      return Response::json(['error'=>'error'], 400);
      }
	}

	public function Addpincode()
	{
		//dd(Input::all());
      $city=Input::get('city');
      $office_name=Input::get('office_name');
      $office_type=Input::get('office_type');
      $pincode=Input::get('pincode');
      $postal_region=Input::get('postal_region');
      $state=Input::get('state');
      $region=Input::get('region');
      $status=Input::get('status');

		$Pincode = new Pincode();
		$Pincode->pincode=$pincode;
		$Pincode->office_type=$office_type;
		$Pincode->office_name=$office_name;
		$Pincode->postal_region=$postal_region;
		$Pincode->region=$region;
		$Pincode->city=$city;
		$Pincode->state=$state;
		$Pincode->status=$status;
		$Pincode->save();

		if($Pincode)
      {
      return Response::json(['success'=>'success'], 200);
      }else
      {
      return Response::json(['error'=>'error'], 400);
      }
	}

	
}