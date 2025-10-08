<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['Pendiente', 'Atrasada', 'Completada', 'En Proceso'])->default('Pendiente');
            $table->dateTime('fecha_limite')->nullable();
            $table->boolean('archivar')->default(false);
            $table->string('archivo')->nullable();
            $table->timestamps();
            $table->integer('id_usuario_reg');
            $table->integer('id_usuario_act');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
