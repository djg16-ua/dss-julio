<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['LEAD', 'SENIOR_DEV', 'DEVELOPER', 'JUNIOR_DEV', 'DESIGNER', 'TESTER', 'ANALYST', 'OBSERVER'])
                ->default('DEVELOPER');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Índices y constraints
            $table->unique(['team_id', 'user_id']);
            $table->index('team_id');
            $table->index('user_id');
            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_user');
    }
};
