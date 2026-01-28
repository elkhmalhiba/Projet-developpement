<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        // L'utilisateur qui reçoit la notification
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->string('title');      // Titre (ex: "Réservation Approuvée")
        $table->text('message');      // Contenu détaillé
        $table->boolean('is_read')->default(false); // Pour savoir si l'utilisateur l'a lue
        $table->timestamps();         // Date de création (utile pour "il y a 5 min")
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
