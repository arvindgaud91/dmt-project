<?php

Route::post('/export/excel', function ()
{
	$name = Input::get('name', 'default').'-'.time();
	Excel::create($name, function($excel) {
    $excel->sheet('Sheetname', function($sheet) {
       $sheet->fromArray(Input::get('rows', []));
    });
	})->store('xls', public_path('exports'));
	return $name;
});