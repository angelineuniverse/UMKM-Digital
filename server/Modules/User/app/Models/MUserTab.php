<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Master\Models\MAccessTab;

class MUserTab extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_access_tabs_id',
        'email',
        'name',
        'phone',
        'password',
        'isactive'
    ];
    protected $appends = [
        'status',
        'access_tab_title',
    ];
    protected $hidden = [
        'password',
    ];

    protected static function newFactory()
    {
        // Create
    }

    public function getAccessTabTitleAttribute()
    {
        $this->mAccessTab->title;
    }

    public function getStatusAttribute()
    {
        if ($this->isactive == 1) {
            return 'ACTIVE';
        } else {
            return 'NOT ACTIVE';
        }
    }

    public function mAccessTab()
    {
        return $this->hasOne(MAccessTab::class, 'id', 'm_access_tabs_id');
    }

    public function tAccessMenuUserTab()
    {
        return $this->hasOne(TAccesMenuUserTab::class, 'm_user_tabs_id', 'id');
    }
}
