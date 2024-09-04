<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MMenuTab;
use Modules\User\Database\Factories\TAccesMenuUserTabFactory;

class TAccesMenuUserTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'm_user_tabs_id',
        'm_menu_tabs_id',
    ];

    protected static function newFactory()
    {
        // Create
    }

    public function menu(){
        return $this->hasOne(MMenuTab::class, 'id','m_menu_tabs_id');
    }
}
