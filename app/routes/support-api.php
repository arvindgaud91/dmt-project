<?php

Route::get('/api/support/v1', 'SupportController@support_all_data');
Route::post('/api/support/v1/submitresponse', 'SupportController@support_response_submit');
?>