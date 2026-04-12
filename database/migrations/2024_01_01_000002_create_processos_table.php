<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();   // ex: 001/2026
            $table->unsignedSmallInteger('ano');
            $table->unsignedInteger('sequencia');
            $table->string('titulo', 255);
            $table->text('descricao')->nullable();
            $table->string('secretaria', 150)->nullable();
            $table->string('tipo', 60);
            $table->enum('status', ['em_andamento','em_analise','aprovado','finalizado','cancelado'])->default('em_andamento');
            $table->text('objeto')->nullable();
            $table->decimal('valor_estimado', 15, 2)->nullable();
            $table->date('data_abertura')->nullable();
            $table->date('data_encerramento')->nullable();
            $table->foreignId('criado_por')->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['ano','sequencia']);
            $table->index('status');
            $table->index('secretaria');
            $table->index('ano');
        });
    }
    public function down(): void { Schema::dropIfExists('processos'); }
};
