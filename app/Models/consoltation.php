<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consoltation extends Model
{
    use HasFactory;
    protected $table ="consoltations";

    public $timestamps = false;
    protected $fillable = ["consoltation"];
}
