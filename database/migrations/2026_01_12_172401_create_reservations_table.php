<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void {
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('resource_id')->constrained()->onDelete('cascade');
        $table->dateTime('start_date');
        $table->dateTime('end_date');
        
        // On définit l'ENUM avec les termes français exacts
        $table->enum('status', ['EN ATTENTE', 'VALIDÉE', 'REFUSÉE'])->default('EN ATTENTE');
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
