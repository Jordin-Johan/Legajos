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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('dni')->length(8)->unique();
            $table->string('direccion')->nullable();
            $table->string('correo')->nullable();
            $table->string('cargo')->nullable();
            $table->string('varEnlace')->nullable();
            $table->boolean('tipoPersonal')->default(1); // 1=activo, 0=eliminado
            $table->tinyInteger('tipoContratado'); // 1=cas, 2=locacion, 3=nombrado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
