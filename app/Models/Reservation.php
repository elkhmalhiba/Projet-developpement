<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // C'est cette liste qui autorise l'enregistrement des données
    protected $fillable = [
        'user_id',
        'resource_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];


    // app/Models/Reservation.php

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ajoute aussi celle pour la ressource si ce n'est pas fait
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
