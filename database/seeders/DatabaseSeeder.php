<?php
namespace Database\Seeders;

use App\Models\Processo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $sa = User::create([
            'name'              => 'Super Administrador',
            'email'             => 'superadmin@ged.gov.br',
            'password'          => Hash::make('password'),
            'role'              => 'super_admin',
            'cargo'             => 'Super Administrador',
            'setor'             => 'TI',
            'ativo'             => true,
            'email_verified_at' => now(),
        ]);

        $admin = User::create([
            'name'              => 'Administrador',
            'email'             => 'admin@ged.gov.br',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'cargo'             => 'Pregoeiro',
            'setor'             => 'Licitações',
            'ativo'             => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Auditor Sistema',
            'email'             => 'auditor@ged.gov.br',
            'password'          => Hash::make('password'),
            'role'              => 'auditor',
            'cargo'             => 'Auditor',
            'setor'             => 'Controladoria',
            'ativo'             => true,
            'email_verified_at' => now(),
        ]);

        // Processos de exemplo
        $exemplos = [
            ['titulo'=>'Aquisição de Materiais de Escritório',    'tipo'=>'pregao_eletronico','status'=>'em_andamento','secretaria'=>'Sec. de Administração', 'valor'=>85000],
            ['titulo'=>'Reforma da Unidade Básica de Saúde',      'tipo'=>'concorrencia',     'status'=>'em_analise',  'secretaria'=>'Sec. de Saúde',         'valor'=>780000],
            ['titulo'=>'Fornecimento de Merenda Escolar',         'tipo'=>'tomada_precos',    'status'=>'aprovado',    'secretaria'=>'Sec. de Educação',       'valor'=>320000],
            ['titulo'=>'Manutenção de Veículos da Frota',         'tipo'=>'dispensa',         'status'=>'finalizado',  'secretaria'=>'Dep. de Transportes',    'valor'=>45000],
            ['titulo'=>'Aquisição de Equipamentos de Informática','tipo'=>'pregao_eletronico','status'=>'em_andamento','secretaria'=>'Dep. de TI',             'valor'=>250000],
        ];

        foreach ($exemplos as $ex) {
            $num = Processo::gerarNumero();
            Processo::create([
                'numero'          => $num['numero'],
                'ano'             => $num['ano'],
                'sequencia'       => $num['sequencia'],
                'titulo'          => $ex['titulo'],
                'tipo'            => $ex['tipo'],
                'status'          => $ex['status'],
                'secretaria'      => $ex['secretaria'],
                'valor_estimado'  => $ex['valor'],
                'objeto'          => 'Objeto do processo: '.$ex['titulo'],
                'data_abertura'   => now()->subDays(rand(10,60)),
                'criado_por'      => $admin->id,
            ]);
        }

        $this->command->info('✅ Seed concluído!');
        $this->command->table(
            ['Perfil','E-mail','Senha'],
            [
                ['Super Admin', 'superadmin@ged.gov.br', 'password'],
                ['Admin',       'admin@ged.gov.br',       'password'],
                ['Auditor',     'auditor@ged.gov.br',     'password'],
            ]
        );
    }
}
