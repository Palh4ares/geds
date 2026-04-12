<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained('processos')->onDelete('cascade');
            $table->string('nome', 255);
            $table->text('descricao')->nullable();
            $table->string('tipo_documento', 60);
            $table->string('arquivo_path', 500);
            $table->string('arquivo_nome_original', 255);
            $table->unsignedBigInteger('arquivo_tamanho');
            $table->string('arquivo_mime', 100);
            $table->string('versao', 20)->default('1.0');
            $table->foreignId('enviado_por')->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            $table->index('processo_id');
            $table->index('tipo_documento');
        });
    }
    public function down(): void { Schema::dropIfExists('documentos'); }
};
