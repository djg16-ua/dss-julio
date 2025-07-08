<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->boolean('is_general')->default(false); // Marca el equipo general del proyecto
            $table->timestamps();
            
            // Índices
            $table->index('name');
            $table->index('project_id');
            $table->index('is_general');
              
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};