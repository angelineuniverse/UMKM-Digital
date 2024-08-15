<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Database\Factories\MUserTabFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MUserTab extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_access_tabs_id',
        'email',
        'password',
        'isactive'
    ];
    protected $hidden = [
        'password',
    ];

    protected static function newFactory()
    {
        // Create
    }
}