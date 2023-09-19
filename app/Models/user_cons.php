<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_cons extends Model
{
    use HasFactory;
    protected $table ="cons_expert";
    public $timestamps = false;
    protected $fillable = ["expert_id","cons_id","c_price"];

    public  function expert(){

    }
}
