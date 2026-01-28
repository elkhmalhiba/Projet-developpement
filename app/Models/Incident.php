<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import des modèles liés
use App\Models\Resource;
use App\Models\User;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'user_id',
        'title',
        'description',
        'status', // ouvert, en cours, résolu
    ];

    // Un incident concerne une ressource
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    // Un incident est signalé par un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
