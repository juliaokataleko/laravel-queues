<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class SaleController extends Controller
{

    function index() {
        return view('upload-file');
    }

    function upload(Request $request) {
        if (request()->hasFile('mycsv')) {

            // $data = array_map('str_getcsv', file(request()->mycsv));
            $data = file(request()->mycsv);

            // Chunking file
            $chunks = array_chunk($data, 1000);

            // convert each 1000 chunks into a new csv file

            $batch = Bus::batch([])->dispatch();

            $header = [];
            foreach ($chunks as $key => $chunk) {

                $data = array_map('str_getcsv', $chunk);
                
                if ($key === 0) {
                    $header = $data[0];
                    unset($data[0]);
                }

                $batch->add(new SalesCsvProcess($data, $header));

                // SalesCsvProcess::dispatch($data, $header);

            }
            
            return $batch;

        }
    
        return "Please upload a file";
    }

    public function batch() {
        $batchId = request('id');

        $batch = Bus::findBatch($batchId);

        return $batch;
    }

}
