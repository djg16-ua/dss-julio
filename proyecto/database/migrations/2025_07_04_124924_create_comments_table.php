<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');

            // CORREGIDO: Comment es específico entre User y Task
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Índices
            $table->index('user_id');
            $table->index('task_id');
            $table->index('created_at');
            $table->index(['user_id', 'task_id']); // Optimización de búsqueda pero no restricción
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
