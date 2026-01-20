<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $fillable = [
        'email',
        'website',
        'status',
        'template_id',
        'notes',
    ];
}
