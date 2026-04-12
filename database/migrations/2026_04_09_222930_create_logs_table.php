<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            // usuário que fez a ação
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // ação realizada
            $table->string('acao'); // ex: criou, editou, excluiu, visualizou

            // entidade afetada
            $table->string('modulo'); // ex: documentos, processos, usuarios

            // id do registro afetado
            $table->unsignedBigInteger('registro_id')->nullable();

            // descrição mais detalhada
            $table->text('descricao')->nullable();

            // IP e navegador (importante pra auditoria)
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};