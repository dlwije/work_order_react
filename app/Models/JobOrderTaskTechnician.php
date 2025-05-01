<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderTaskTechnician extends Model
{
    use HasFactory;

    protected $fillable = [
        'jo_id',
        'jo_task_id',
        'tech_id',
    ];
}
