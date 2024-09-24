<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAuthority extends Model
{
    use HasFactory;

    public function target_group(){
        return $this->belongsTo(Group::class, 'target_group_id');
    }
}
