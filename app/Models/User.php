<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BehinInit\App\Http\Controllers\AccessController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    public $incrementing = false; 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? substr(str_shuffle('123456789'), 0, 9);
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    function access($method_name)
    {
        return (new AccessController($method_name))->check();
    }
}
