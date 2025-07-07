<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'DONE', 'PAUSED', 'CANCELLED'])
                ->default('PENDING');
            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])->default('MEDIUM');
            $table->datetime('end_date')->nullable();
            $table->timestamp('completed_at')->nullable();

            // CLAVE: Task depende de Module (y por tanto de Project)
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Dependencias - Task que bloquea esta task
            $table->foreignId('depends_on')->nullable()->constrained('tasks')->onDelete('set null');

            $table->timestamps();

            // Índices
            $table->index('status');
            $table->index('priority');
            $table->index('end_date');
            $table->index('module_id'); // IMPORTANTE
            $table->index('assigned_to');
            $table->index('created_by');
            $table->index('depends_on');
            $table->index('completed_at');

            // Constraint: título único por módulo
            $table->unique(['module_id', 'title']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
