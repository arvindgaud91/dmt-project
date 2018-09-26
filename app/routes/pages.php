<?php

Route::get('/distributors', 'PagesController@getMyDistributorsPage');
Route::get('/distributors/{distId}/agents', 'PagesController@getDistributorAgents');
Route::get('/agents', 'PagesController@getMyAgentsPage');
