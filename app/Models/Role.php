<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Autorise l'insertion du nom du rôle
    protected $fillable = ['name'];

    // Un rôle peut avoir plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class);
    }
}