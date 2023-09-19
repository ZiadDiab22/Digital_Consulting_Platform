<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_expert extends Model
{
    use HasFactory;
    protected $table ="users_experts";
    public $timestamps = false;
    protected $fillable = ["c_expert_id","user_id","date_id","evaluation"];
}

