<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'website',
        'status',
        'template_id',
        'notes',
    ];
}
