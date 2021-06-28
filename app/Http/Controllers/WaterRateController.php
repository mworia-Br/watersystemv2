<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaterRate;
use Illuminate\Support\Facades\Validator;

class WaterRateController extends Controller
{
    private function customValidator($requestData)
    {
        $validator = Validator::make($requestData,[
            'type' => 'required',
            'min_rate' => 'required|numeric',
            'excess_rate' => 'required|numeric'
        ],[
            'type' => 'Water Type should not be empty',
            'min_rate' => 'Min Rate should not be empty',
            'excess_rate' => 'Excess Rate should not be empty'
        ]);
        return $validator;
    }

    public function update(Request $request)
    {
        $validator = $this->customValidator($request->all());
        if($validator->fails()){
            return response()->json(['updated' => false, 'errors' => $validator->errors()]);
        }

        $requestData = $request->all();

        $requestData['min_rate'] = $requestData['min_rate'] ? $requestData['min_rate'] : '0';
        $requestData['excess_rate'] = $requestData['excess_rate'] ? $requestData['excess_rate'] : '0';

        $water_rate = WaterRate::find($requestData['type']);

        $min_rate = $requestData['min_rate'] / 100;
        $excess_rate = $requestData['excess_rate'] / 100;

        $water_rate->min_rate = $min_rate;
        $water_rate->excess_rate = $excess_rate;
        $water_rate->save();

        return response()->json(['updated' => true, 'message' => "$water_rate->type has been successfully updated"]);
    }

    public function getWaterRates()
    {
        return response()->json(['data' => WaterRate::all()]);
    }
}
