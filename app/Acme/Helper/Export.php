<?php
namespace Acme\Helper;
class Export {
    public function exportData($records,$report_name){
        if ($records) {
            // dd($products);
            return \Excel::create($report_name.time(), function($excel) use ($records) {
                        $excel->sheet('sheet name', function($sheet) use ($records) {
                            $sheet->fromArray($records);
                        });
                    })->download('csv');
        } else {
            $records[0] = "No Records";
            return \Excel::create($report_name.time(), function($excel) use ($records) {
                        $excel->sheet('sheet name', function($sheet) use ($records) {
                            $sheet->fromArray($records);
                        });
                    })->download('csv');
        }
    }
}