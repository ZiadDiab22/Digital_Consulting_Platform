<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\date;
use Illuminate\Http\Request;
use App\Models\user_expert;
use function PHPUnit\Framework\isEmpty;

class evaluation extends Controller
{
    public function get_evaluation($id)
    {
        $sub = 0;
        $n = 0;
        $count = $evaluation = user_expert::get()->count();
        for ($i = 1; $i <= $count; $i++) {
            $evaluation = user_expert::where("id", $i)->where("c_expert_id", $id)->first();
            if ($evaluation != null)
                if ($evaluation->evaluation != 0) {
                    $sub = ($sub + $evaluation->evaluation);
                    $n++;
                }
        }

        return response()->json([
            "status" => 1,
            "message" => "expert evaluation evaluation",
            "all evaluation" => $n,
            "evaluation" => $sub/$n
        ]);
    }

    //what user set evaluation to expert *_*
    public function get_user_evaluation(Request $request)
    {
        $request->validate([
            'c_expert_id' => 'required',
        ]);

        $evaluation = user_expert::where("c_expert_id", $request->c_expert_id)->where("user_id", auth()->user()->id)->get();
        return response()->json([
            "status" => 1,
            "message" => "user evaluation",
            "data" => $evaluation
        ]);
    }

    //Add evaluation
    public function set_evaluation(Request $request)
    {
        $request->validate([
            'c_expert_id' => 'required',
            'evaluation' => 'required'
        ]);
        $evaluation = user_expert::where("c_expert_id", $request->c_expert_id)->where("user_id", auth()->user()->id)->first();
        if (empty($evaluation)) {
            $request->validate([
                'c_expert_id' => 'required',
                'evaluation' => 'required'
            ]);
            $evaluation = new user_expert();
            $evaluation->c_expert_id = $request->c_expert_id;
            $evaluation->user_id = auth()->user()->id;
            $evaluation->date_id = 0;
        }

        $evaluation->evaluation = $request->evaluation;
        $evaluation->save();
        return response()->json([
            "status" => 1,
            "message" => "stored evaluation",
            "data" => $evaluation
        ]);
    }

    public function booking(Request $request)
    {
        $request->validate([
            'c_expert_id' => 'required',
            'date_id' => 'required'
        ]);
        $date = user_expert::where("c_expert_id", $request->c_expert_id)->where("user_id", auth()->user()->id)->first();
        if (empty($evaluation)) {
            $request->validate([
                'c_expert_id' => 'required',
                'date_id' => 'required'
            ]);
            $date = new user_expert();
            $date->c_expert_id = $request->c_expert_id;
            $date->user_id = auth()->user()->id ;
            $date->evaluation = 0;
        }

        $date->date_id = $request->date_id;

        $date->save();
        return response()->json([
            "status" => 1,
            "message" => "stored evaluation",
            "data" => $date
        ]);
    }

}
