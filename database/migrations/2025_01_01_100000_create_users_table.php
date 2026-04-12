<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Se a tabela já existe (criada por migration do framework), apenas adiciona colunas faltantes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'cargo')) {
                    $table->string('cargo', 100)->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'setor')) {
                    $table->string('setor', 100)->nullable()->after('cargo');
                }
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['super_admin','admin','setor','usuario','auditor'])
                          ->default('usuario')->after('setor');
                }
                if (!Schema::hasColumn('users', 'ativo')) {
                    $table->boolean('ativo')->default(true)->after('role');
                }
            });
            return;
        }

        // Cria do zero se não existir
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cargo', 100)->nullable();
            $table->string('setor', 100)->nullable();
            $table->enum('role', ['super_admin','admin','setor','usuario','auditor'])->default('usuario');
            $table->boolean('ativo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Só dropa se foi criada por nós (tem a coluna 'role' que o L12 não tem por padrão)
        if (Schema::hasColumn('users', 'role')) {
            // Se a tabela foi criada por nós, dropa tudo
            // Se foi criada pelo framework, remove apenas nossas colunas
            $columns = ['cargo', 'setor', 'role', 'ativo'];
            $existingColumns = array_filter($columns, fn($c) => Schema::hasColumn('users', $c));
            if (!empty($existingColumns)) {
                Schema::table('users', fn(Blueprint $t) => $t->dropColumn($existingColumns));
            }
        }
    }
};
