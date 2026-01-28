<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('resources', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('resource_category_id'); 
        $table->string('cpu')->nullable();
        $table->string('ram')->nullable();
        $table->string('bandwidth')->nullable();
        $table->string('capacity')->nullable();
        $table->string('os')->nullable();
        $table->string('location')->nullable();
        $table->string('status')->default('available');
        
        $table->timestamps();

        // Relation vers la table des catégories
        $table->foreign('resource_category_id')->references('id')->on('resource_categories')->onDelete('cascade');
    });
}
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
