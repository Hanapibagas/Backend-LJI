<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DailyReportController extends Controller
{
    public function getDailyReport(){
        $data = DailyReport::where('user_id',Auth::user()->id)->get();
        return response()->json([
            'message' => 'Data Daily Report',
            'data' => $data
        ],200);
    }

    public function storeDailyReport(Request $request){
        $daily_report = DailyReport::whereDate('created_at',date('Y-m-d'))->first();
        if($daily_report){
            return response()->json([
                'message' => "Today's report already exists"
            ],422);
        }
        $data_validate = $request->all();
        $validator = Validator::make($data_validate, [
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ],422);
        }
        $data = new DailyReport;
        $data->user_id = Auth::user()->id;
        $data->description = $request->description;
        $data->save();
        return response()->json([
            'message' => 'Success Create Daily Report',
            'data' => $data
        ],200);
    }

    public function updateDailyReport(Request $request,$id){
        $data_validate = $request->all();
        $validator = Validator::make($data_validate, [
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ],422);
        }
        $data = DailyReport::findOrFail($id);
        $data->user_id = Auth::user()->id;
        $data->description = $request->description;
        $data->save();
        return response()->json([
            'message' => 'Success Update Daily Report',
            'data' => $data
        ],200);
    }
}
