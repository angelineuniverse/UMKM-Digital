<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\Factories\MAccessTabFactory;

class MAccessTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory()
    {
        // Create
    }

    public function access_menu(){
        return $this->hasMany(MAccesMenuTab::class,'m_access_tabs_id','id');
    }
}
