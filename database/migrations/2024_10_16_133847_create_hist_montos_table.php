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
        Schema::create('TBL_Hist_Monto', function (Blueprint $table) {
            $table->id();
            $table->decimal('Monto_Anterior', 15, 2)->nullable();
            $table->decimal('Monto_Nuevo', 15, 2)->nullable();
            $table->date('Fecha_Modificacion');
            $table->string('Modificado_Por');
            $table->foreignId('ID_Proyecto')
                ->constrained('TBL_Proyecto', 'Id_Proyecto')
                ->onDelete('cascade');
            $table->string('TipoMonto', 50);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hist_montos');
    }
};
