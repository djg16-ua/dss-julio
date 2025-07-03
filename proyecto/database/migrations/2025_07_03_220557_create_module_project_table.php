<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('module_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->date('start_date')->nullable();
            $table->date('expected_completion')->nullable();
            $table->timestamps();

            // Índices y constraints
            $table->unique(['module_id', 'project_id']);
            $table->index('module_id');
            $table->index('project_id');
            $table->index('is_primary');
        });
    }

    public function down()
    {
        Schema::dropIfExists('module_project');
    }
};
