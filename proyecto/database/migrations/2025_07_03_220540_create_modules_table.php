<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])->default('MEDIUM');
            $table->enum('category', [
                'DEVELOPMENT',
                'DESIGN',
                'TESTING',
                'DOCUMENTATION',
                'RESEARCH',
                'DEPLOYMENT',
                'MAINTENANCE',
                'INTEGRATION'
            ])->default('DEVELOPMENT');
            $table->enum('status', ['PENDING', 'ACTIVE', 'DONE', 'PAUSED', 'CANCELLED'])
                ->default('PENDING');
            $table->foreignId('technical_lead_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->boolean('is_core')->default(false);
            $table->timestamps();

            // Índices
            $table->index('priority');
            $table->index('category');
            $table->index('status');
            $table->index('name');
            $table->index('technical_lead_id');
            $table->index('is_core');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
