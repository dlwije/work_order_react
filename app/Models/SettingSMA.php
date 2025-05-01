<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingSMA extends Model
{
    use HasFactory;

    protected $table = "sma_settings";

    public static function getSettingSma(){

        return self::select('*')->first();
    }
}
