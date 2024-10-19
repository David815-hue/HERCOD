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
        Schema::create('TBL_Proyecto', function (Blueprint $table) {
            $table->id('Id_Proyecto');
            $table->integer('NumeroContrato');
            $table->string('NumeroLicitacion');
            $table->string('Nombre_Proyecto');
            $table->string('Descripcion');
            $table->string('Estado')->default('En progreso');
            $table->date('Fecha_FirmaContrato');
            $table->date('Fecha_OrdenInicio');
            $table->decimal('Anticipo', 15, 2);
            $table->decimal('Monto_Contractual', 15, 2);
            $table->decimal('Monto_Final', 15, 2)->nullable();
            $table->date('Fecha_Fin')->nullable();
            $table->string('Direccion');
            $table->date('Fecha_Creacion')->default(now());
            $table->integer('ID_Empresa');
            $table->integer('ID_Municipio');
            $table->integer('Encargado');
            $table->string('Creado_Por')->nullable();
            $table->string('Modificado_Por')->nullable();
            $table->date('Fecha_Modificacion')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TBL_Proyecto');
    }
};
