<?php

namespace App\Http\Controllers\Api;
use App\Models\favourite;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\user_cons;
use Illuminate\Http\Request;
use App\Models\date;
use App\Models\consoltation;
use App\Models\user_expert;
use function Ramsey\Uuid\Codec\decode;
use function Ramsey\Uuid\Codec\encode;

class expertController extends Controller
{
    public function AddCon(Request $req){

        $arr=$req->consoltation;
        foreach ($arr as $i){
            $con = new consoltation();
            $con->consoltation = $i;
            $con->save();
        }

        return response()->json([
            "status"=>1,
            "message"=>"added successfully"
        ]);
    }


    public function AddTime($id ,Request $req){

        $arr =$req->date;
        foreach ($arr as $i){
            $date = new date();

            $date->date = $i;
            $date->expert_id = $id;
            $date->save();}
        return response()->json([
            "status"=>1,
            "message"=>"added successfully"
        ]);
    }
    public function Add_one_Time(Request $req){
            $id=auth()->user()->id;
            $date = new date();
            $date->date = $req->date;
            $date->expert_id = $id;
            $date->save();
        return response()->json([
            "status"=>1,
            "message"=>"added successfully"
        ]);
    }
    public function list_experts_cons($id)
    {
        $experts=user_cons::where("cons_id",$id)->get();
        $arr =json_decode($experts,true) ;

        return response()->json([
            "status"=>1,
            "message"=>"experts:",
            "data"=>$arr
        ]);
    }
    public function list_experts_info_cons($id){
        $var1 = user_cons::where('cons_id',$id)->get();
        $arr = array();
        foreach ($var1 as $i){
            $var2 = User::where('id',$i['expert_id'])->get(['id','name' , 'email' , 'phone_no' , 'imgURL' , 'experiences']);
            $arr1=json_decode($var2,true);
            $arr1[0] = array_merge($arr1[0],array ("c_price"=>$i['c_price']));
            if (user_expert::where('evaluation','!=',0)->where('c_expert_id',$i['expert_id'])->exists()) {
                $sum = 0;
                $v = user_expert::where('evaluation', '!=', 0)->where('c_expert_id', $i['expert_id'])->get();
                $n = $v->count();
                foreach ($v as $x) { $sum = $sum + ($x['evaluation']); }
                $arr1[0] = array_merge($arr1[0], array("votes number" => $n, "average rate" => ($sum / $n)));
            }
            $arr= array_merge($arr,$arr1);
        }
        return response()->json([
            "status"=>1,
            "message"=>" ",
            "data"=> $arr ]);
    }

    public function ShowCon(){
        $con = consoltation::get();
        return response()-> json([
            "status"=>1,
            "message"=>"All consultations",
            "data"=> $con ]);
    }
    public function get_date($id){
        $date=date::where('id',$id)->get();
        return response()-> json([
            "status"=>1,
            "message"=>"date info",
            "data"=> $date ]);
    }
    public function AddexpertCon(Request $req){
        $row = new user_cons();
        $row->expert_id = auth()->user()->id;

        $row->c_price =$req->c_price;
        $cons_id_var=consoltation::where("consoltation",$req->cons)->get("id")->first();
        if($cons_id_var!=null)
            $row->cons_id=$cons_id_var->id;
        if($cons_id_var==null) {
            $cons=new consoltation();
            $cons->consoltation=$req->cons;
            $cons->save();
            $cons_id=consoltation::where("consoltation",$req->cons)->get("id")->first();
            $row->cons_id=$cons_id->id;
        }
        $row->save();

        return response()->json([
            "status"=>1,
            "message"=>"added sucessfuly",
            "data"=>$row
        ]); }
    public function ShowValidTimes(){
        $id=auth()->user()->id;
        $obj = User::find($id)->Times;
        $arr = json_decode($obj,true); $arr2 = array();
        $m=0;
        foreach($arr as $i){
            array_push($arr2 , $i['id']); } foreach($arr2 as $i){
            if (user_expert::where('date_id',$i)->exists()) unset($arr2[$m]);
            $m++; }
        $arr3 = array();
        foreach($arr2 as $i){
            array_push($arr3,date::where('id',$i)->
            get(['date' , 'id'])); }
        $arr4 = array();
        $arr5 = array();
        foreach($arr3 as $i){
            array_push($arr4 , $i[0]['date']);
            array_push($arr5 , $i[0]['id']);

        }
        $arr4 = array_map('trim',$arr4);

        return response()->
        json([ "status"=>1, "Valid Times for expert "=> $arr4 , "Times id"=> $arr5 ]);}

        public function addFavourite(Request $req){
           // $id=auth()->user()->id;
            $row = new favourite();
            $row->user_id = $req->user_id;
            $row->expert_id = $req->expert_id;
            $row->save();
            return response()->json([
                "status"=>1,
                "message"=>"added successfully"
            ],200);}

    public function DeleteFavourite(Request $req){
        // $id=auth()->user()->id;
        favourite::where("expert_id",$req->expert_id)->where("user_id",$req->user_id)->delete();

        return response()->json([
            "status"=>1,
            "message"=>"Deleted successfully"
        ],200);}

    public function getFavourites(){
        $var = favourite::distinct()->get(['expert_id']);
        $arr=array();
        foreach ($var as $i){
            $var2 = User::where('id',$i['expert_id'])->get(['name']);
           array_push($arr,$var2[0]['name']) ;
        }
        return response()->json([
            "status"=>1,
            "favourite experts"=> $arr
        ],200);}

    public function get_one_Favourites($id){
        $var = favourite::where('user_id',$id)->get(['expert_id']);
        $arr=array();
        foreach ($var as $i){
            $var2 = User::where('id',$i['expert_id'])->get(['name']);
            array_push($arr,$var2[0]['name']) ;
        }
        return response()->json([
            "status"=>1,
            "favourite experts"=> $arr
        ],200);}
}
