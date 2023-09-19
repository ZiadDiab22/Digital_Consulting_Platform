<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class date extends Model
{
    use HasFactory;
    protected $table ="dates";
    public $timestamps = false;
    protected $fillable = ["expert_id","date"];
}
