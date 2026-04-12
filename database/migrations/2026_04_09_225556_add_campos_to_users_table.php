<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cargo')) {
                $table->string('cargo', 100)->nullable();
            }

            if (!Schema::hasColumn('users', 'setor')) {
                $table->string('setor', 100)->nullable();
            }

            if (!Schema::hasColumn('users', 'ativo')) {
                $table->boolean('ativo')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cargo', 'setor', 'ativo']);
        });
    }
};