<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'DONE', 'PAUSED', 'CANCELLED'])
                ->default('PENDING');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('public')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Índices
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('public');
            $table->index('created_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};