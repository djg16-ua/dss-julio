<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            // Índices y constraints
            $table->unique(['user_id', 'project_id']);
            $table->index('user_id');
            $table->index('project_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_project');
    }
};