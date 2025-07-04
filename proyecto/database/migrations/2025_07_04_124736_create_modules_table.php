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

            // CLAVE: Module depende de Project
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('depends_on')->nullable()->constrained('modules')->onDelete('set null');
            $table->boolean('is_core')->default(false);
            $table->timestamps();

            // Índices
            $table->index('priority');
            $table->index('category');
            $table->index('status');
            $table->index('name');
            $table->index('project_id'); // IMPORTANTE

            $table->index('is_core');

            // Constraint: nombre único por proyecto
            $table->unique(['project_id', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
