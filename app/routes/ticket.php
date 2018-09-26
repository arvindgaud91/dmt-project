<?php

Route::get('/add-ticket', 'TicketController@getAddTicket');
Route::get('/all-ticket', 'TicketController@getAllTicket');
Route::get('/view-ticket/{id}', 'TicketController@getTicketDetail');
Route::post('/api/v1/ticket/add-ticket', 'TicketController@postGenerateTicket');
Route::post('/api/v1/ticket/add-customer', 'TicketController@postImportCustomer');
Route::post('/api/v1/ticket/insert-comment', 'TicketController@postInsertComment');
Route::post('/api/v1/ticket/current-status', 'TicketController@getTicketCurrentStatus');
Route::post('/api/v1/ticket/status-history', 'TicketController@getTicketStatusHistory');
Route::post('/api/v1/ticket/product-issue-list/{product_name}', 'TicketController@getProductIssueList');
Route::get('/ajax-all-tickets','TicketController@ajaxAllTickets');
