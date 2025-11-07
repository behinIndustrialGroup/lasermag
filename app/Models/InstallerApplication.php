<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'national_id',
        'phone',
        'province',
        'city',
        'description',
    ];
}
