<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\Uid\Factory\create;

class user_controller extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_expert' =>"required",
            "phone_no" => "required",
            "experiences"=>"required"
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_expert' =>$request->is_expert,
            "balance"=>10000,
            'imgURL'=>"",
            'phone_no' =>$request->phone_no,
            "experiences"=>$request->experiences
        ]);

        $token = Auth::login($user);
        return response()->json([

            'status' => 1,
            'message' => 'User created successfully',
            'user_id'=>$user->id,
            "access_token"=>$token
        ]);
    }
    public function register_img(Request $request){
        $id=auth()->user()->id;

        $imgurl=$request->file('imgURL')->store('apiDocs');
        $imgname=basename($imgurl);
        $user=User::where("id",$id)->first();
        $user->imgURL="http://127.0.0.1:8000/download/?file=$imgname";
        $user->save();
        return response()->json([
            'status' => 1,
            'message' => 'User img stored successfully',
            'data'=>$user
        ]);
    }
    public function install_img(Request $request){
        if(Storage::disk('local')->exists("apiDocs/$request->file")){
            $path=Storage::disk("local")->path("apiDocs/$request->file");
            $content=file_get_contents($path);
            return response($content)->withHeaders(['Content-Type'=>mime_content_type($path)]);
        }
    }
    public function login (Request $request)
    {
        $request->validate([
            "email"=>"required|email",
            "password"=>"required"
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                "status"=>0,
                "message"=>"undefined user"
            ]);
        }
        $user = Auth::user();
        return response()->json([
            'status' => 1,
            'user' => $user,
            'token' =>Auth::attempt($credentials)
        ]);
    }
    public function profile()
    {
        return response()->json([
            "status"=>1,
            "message"=>"profile info",
            "data"=>auth()->user()
        ]);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status"=>1,
            "message"=>"logout successfully"
        ]);
    }
    public function list_experts()
    {
        $experts=User::where("is_expert",1)->get();

        return response()->json([
            "status"=>1,
            "message"=>"all experts",
            "data"=>$experts
        ]);
    }
    public function list_experts_name(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $experts=User::where("name",$request->name)->where("is_expert",1)->get();
        return response()->json([
            "status"=>1,
            "message"=>"experts by name",
            "data"=>$experts
        ]);
    }
    public function get_expert($id){
        $expert=User::where("id",$id)->get(["name","email","phone_no","experiences","imgURL"]);
        return response()->json([
            "status"=>1,
            "message"=>"experts by id",
            "data"=>$expert
        ]);
    }
    public  function show_conversation(Request $request){
        $sender_id=auth()->user()->id;
        $request->validate([
            "receiver_id"=>"required"
        ]);
        $conversation=message::where("sender_id",$sender_id)->where("receiver_id",$request->receiver_id)->orWhere("sender_id",$request->receiver_id)->where("receiver_id",$sender_id)->get();
        return response()->json([
            "status"=>1,
            "message"=>"sended message",
            "data"=>$conversation
        ]);
    }
    public  function send_message(Request $request){
        $sender_id=auth()->user()->id;
        $request->validate([
            "receiver_id"=>"required",
            "message"=>"required"
        ]);
        $user = Auth::user();
        $message=new message();
           $message->sender_id = $sender_id;
            $message-> receiver_id=$request->receiver_id;
            $message->message=$request->message;
        $message->save();
        return response()->json([
            "status"=>1,
            "message"=>"sended message",
            "data"=>$message
        ]);
    }
}
