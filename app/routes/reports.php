<?php

Route::get('/request-reports','ReportsController@getRequestReport');


Route::post('/request-reportsdaywise','ReportsController@getRequestReportdaywise');

Route::get('/request-reportsdaywise','ReportsController@getRequestReport');


Route::post('/request-reportsdaywiseexport','ReportsController@getRequestReportdaywiseexport');



Route::post('/export-requests-report', 'ReportsController@getRequestExport');