<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\Factories\MMenuTabFactory;

class MMenuTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'isactive',
        'url',
        'icon',
        'parent_id',
    ];

    protected $appends = [
        'show'
    ];

    public function getShowAttribute(){
        return false;
    }

    protected static function newFactory()
    {
        // Create
    }

    public function children(){
        return $this->hasMany(MMenuTab::class, 'parent_id','id');
    }
}
