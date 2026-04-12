<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('acao', 80);          // ex: processo.criado, documento.upload
            $table->string('modelo', 60)->nullable();  // Processo, Documento, User
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->text('descricao');
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['modelo','modelo_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }
    public function down(): void { Schema::dropIfExists('auditorias'); }
};
