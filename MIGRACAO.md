# Solução para erros de tabela não encontrada

## Contexto

O sistema foi detectado rodando no **Laravel 12**. Siga os passos abaixo para resolver.

---

## Passo 1 — Verificar o que já existe no banco

```bash
php artisan migrate:status
```

Se aparecer erro ou tabelas pendentes, siga para o Passo 2.

---

## Passo 2 — Rodar as migrations

```bash
php artisan migrate
```

Se der erro de tabela duplicada (`Table already exists`):

```bash
php artisan migrate --pretend
```

Isso mostra o SQL que seria executado sem rodar — útil para diagnóstico.

---

## Passo 3 — Se a tabela `users` já existir mas sem os campos do GED

O Laravel 12 cria automaticamente a tabela `users` com campos básicos.
Nossa migration detecta isso e adiciona apenas os campos que faltam (`cargo`, `setor`, `role`, `ativo`).

Se ainda der erro, rode manualmente no MySQL:

```sql
ALTER TABLE users
  ADD COLUMN IF NOT EXISTS cargo VARCHAR(100) NULL AFTER email,
  ADD COLUMN IF NOT EXISTS setor VARCHAR(100) NULL AFTER cargo,
  ADD COLUMN IF NOT EXISTS role ENUM('super_admin','admin','setor','usuario','auditor') NOT NULL DEFAULT 'usuario' AFTER setor,
  ADD COLUMN IF NOT EXISTS ativo TINYINT(1) NOT NULL DEFAULT 1 AFTER role;
```

---

## Passo 4 — Criar tabelas do GED que não existem

Se as tabelas `processos`, `documentos` ou `auditorias` não existirem:

```bash
php artisan migrate --path=database/migrations/2025_01_01_100001_create_processos_table.php
php artisan migrate --path=database/migrations/2025_01_01_100002_create_documentos_table.php
php artisan migrate --path=database/migrations/2025_01_01_100003_create_auditorias_table.php
```

---

## Passo 5 — Rodar o seed (dados iniciais)

```bash
php artisan db:seed
```

Cria os usuários padrão:

| Perfil     | E-mail                    | Senha    |
|------------|---------------------------|----------|
| Super Admin | superadmin@ged.gov.br    | password |
| Admin       | admin@ged.gov.br         | password |
| Auditor     | auditor@ged.gov.br       | password |

---

## Passo 6 — Se TUDO falhar: migração manual completa

Rode este SQL diretamente no MySQL (substitua `sistemaged` pelo nome do seu banco):

```sql
-- Adicionar colunas na tabela users (se não existirem)
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `cargo` VARCHAR(100) NULL AFTER `email`,
  ADD COLUMN IF NOT EXISTS `setor` VARCHAR(100) NULL AFTER `cargo`,
  ADD COLUMN IF NOT EXISTS `role` ENUM('super_admin','admin','setor','usuario','auditor') NOT NULL DEFAULT 'usuario' AFTER `setor`,
  ADD COLUMN IF NOT EXISTS `ativo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `role`;

-- Criar tabela processos
CREATE TABLE IF NOT EXISTS `processos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` VARCHAR(20) NOT NULL UNIQUE,
  `ano` SMALLINT UNSIGNED NOT NULL,
  `sequencia` INT UNSIGNED NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `descricao` TEXT NULL,
  `secretaria` VARCHAR(150) NULL,
  `tipo` VARCHAR(60) NOT NULL,
  `status` ENUM('em_andamento','em_analise','aprovado','finalizado','cancelado') NOT NULL DEFAULT 'em_andamento',
  `objeto` TEXT NULL,
  `valor_estimado` DECIMAL(15,2) NULL,
  `data_abertura` DATE NULL,
  `data_encerramento` DATE NULL,
  `criado_por` BIGINT UNSIGNED NOT NULL,
  `deleted_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `processos_ano_sequencia_unique` (`ano`, `sequencia`),
  INDEX `processos_status_index` (`status`),
  INDEX `processos_secretaria_index` (`secretaria`),
  INDEX `processos_ano_index` (`ano`),
  FOREIGN KEY (`criado_por`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar tabela documentos
CREATE TABLE IF NOT EXISTS `documentos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `processo_id` BIGINT UNSIGNED NOT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `descricao` TEXT NULL,
  `tipo_documento` VARCHAR(60) NOT NULL,
  `arquivo_path` VARCHAR(500) NOT NULL,
  `arquivo_nome_original` VARCHAR(255) NOT NULL,
  `arquivo_tamanho` BIGINT UNSIGNED NOT NULL,
  `arquivo_mime` VARCHAR(100) NOT NULL,
  `versao` VARCHAR(20) NOT NULL DEFAULT '1.0',
  `enviado_por` BIGINT UNSIGNED NOT NULL,
  `deleted_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `documentos_processo_id_index` (`processo_id`),
  FOREIGN KEY (`processo_id`) REFERENCES `processos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`enviado_por`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar tabela auditorias
CREATE TABLE IF NOT EXISTS `auditorias` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `acao` VARCHAR(80) NOT NULL,
  `modelo` VARCHAR(60) NULL,
  `modelo_id` BIGINT UNSIGNED NULL,
  `descricao` TEXT NOT NULL,
  `ip` VARCHAR(45) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `auditorias_modelo_modelo_id_index` (`modelo`, `modelo_id`),
  INDEX `auditorias_user_id_index` (`user_id`),
  INDEX `auditorias_created_at_index` (`created_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## Verificação final

```bash
php artisan migrate:status
```

Todos os arquivos devem aparecer como **Ran**.
