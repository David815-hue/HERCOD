<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('TBL_Usuarios', function (Blueprint $table) {
            $table->boolean('force_renew_password')->default(false)->after('password'); 
            $table->timestamp('last_renew_password_at')->nullable()->after('force_renew_password'); 
        });
    }

    public function down()
    {
        Schema::table('TBL_Usuarios', function (Blueprint $table) {
            $table->dropColumn(['force_renew_password', 'last_renew_password_at']); 
        });
    }
};
