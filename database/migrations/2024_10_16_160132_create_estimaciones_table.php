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
        Schema::create('TBL_Estimaciones', function (Blueprint $table) {
            $table->id('ID_Estimacion');
            $table->decimal('Estimacion', 15, 2);
            $table->date('Fecha_Estimacion');
            $table->date('Fecha_Subsanacion')->nullable();
            $table->foreignId('ID_Proyecto')
                ->constrained('TBL_Proyecto', 'Id_Proyecto')
                ->onDelete('cascade');
            $table->string('Creado_Por');
            $table->date('Fecha_Creacion')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimaciones');
    }
};
