<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('module_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            // Índices y constraints
            $table->unique(['module_id', 'team_id']);
            $table->index('module_id');
            $table->index('team_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('module_team');
    }
};
