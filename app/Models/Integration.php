<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'slug',
        'profile',
        'account',
        'client_id',
        'client_secret',
        'auth_token'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
