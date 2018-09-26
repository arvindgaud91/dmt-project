<?php 
Route::post('/api/v1/vendors', 'VendorsController@postAddDmtVendor');
Route::post('/api/v1/vendors/update/{id}', 'VendorsController@postUpdateDmtVendor');
Route::get('/api/v1/vendors/{id}', 'VendorsController@getDmtVendor');
Route::get('/api/v1/vendors', 'VendorsController@getDmtVendorsPaginate');
//Route::get('/api/v1/vendors/paginate', 'VendorsController@getDmtVendorsPaginate');
Route::delete('/api/v1/vendors/dmt/{id}', 'VendorsController@deleteDmtVendor');

Route::get('/api/v1/vendors/{id}/agent-transaction','VendorsController@getAgentTransactionReport');
Route::get('/api/v1/vendors/{id}/agent-wallet', 'VendorsController@getAgentWalletReport');
// Route::get('/api/v1/vendors/{id}/agent-commission', 'VendorsController@getAgentCommissionReport');

Route::get('/api/v1/dmt/vendors', 'VendorsController@getDmtVendorListData');

?>

