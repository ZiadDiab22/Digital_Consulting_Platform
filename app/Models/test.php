<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    use HasFactory;
    protected $fillable = ["expert_id","cons_id","price","name","email","phone_no","imgURL","n_eva","c_eva"];
}
