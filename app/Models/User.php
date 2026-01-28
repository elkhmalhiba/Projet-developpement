<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    // Méthode pour ajouter une notification
    public function addNotification($title, $message)
    {
        return $this->notifications()->create([
            'title' => $title,
            'message' => $message,
            'is_read' => false
        ]);
    }

    // Méthodes d'aide
    public function isAdmin()
    {
        return $this->role_id === 1; // ID de l'admin dans votre seeder
    }

    public function isTech()
    {
        return $this->role_id === 2; // ID du responsable technique
    }

    public function isUser()
    {
        return $this->role_id === 3; // ID de l'utilisateur interne
    }

    // Ajoutez cette méthode dans la classe User
public function managedResources()
{
    // Pour les responsables techniques, on suppose qu'ils gèrent les ressources
    // selon leur catégorie ou une relation spécifique
    // Pour le moment, retournons toutes les ressources (à adapter selon vos besoins)
    
    if ($this->role_id == 2) { // Responsable technique
        // Vous pouvez ajouter une logique spécifique ici
        // Par exemple, si vous avez un champ `managed_category_id` dans la table users
        // return Resource::where('resource_category_id', $this->managed_category_id)->get();
        
        // Pour l'instant, retournons toutes les ressources
        return Resource::all();
    }
    
    return collect(); // Retourne une collection vide pour les autres rôles
}
}