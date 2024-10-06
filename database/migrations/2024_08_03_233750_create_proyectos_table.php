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
        Schema::create('tbl_proyectos', function (Blueprint $table) {
            $table->id('ID_Proyecto');
            $table->string('NumeroContrato')->unique();
            $table->string('NumeroLicitacion')->unique();
            $table->string('Nombre_Proyecto');
            $table->text('Descripcion');
            $table->decimal('Anticipo', 10, 2);
            $table->date('Fecha_FirmaContrato');
            $table->date('Fecha_OrdenInicio');
            $table->date('Fecha_Fin');
            $table->string('Direccion');
            $table->decimal('Monto_Contractual', 10, 2);
            $table->decimal('Monto_Final', 10, 2);
            $table->unsignedBigInteger('ID_Estado');
            $table->unsignedBigInteger('ID_Empresa');
            $table->unsignedBigInteger('ID_Tipo');
            $table->unsignedBigInteger('ID_Departamento');
            $table->unsignedBigInteger('ID_Municipio');
            $table->unsignedBigInteger('Creado_Por');
            $table->unsignedBigInteger('Modificado_Por')->nullable();
            $table->timestamps();

            
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_proyectos');
    }
};
