<?php
use Acme\Auth\Auth;

class CommissionsController extends BaseController 
{

	public getCommissionReports(){

	// $commissionReportData=DB::table('')->get();
    return View::make('reports.commission-reports');
	}


}
