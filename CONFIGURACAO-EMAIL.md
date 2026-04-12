# Configuração de E-mail — GED Licitações
## Guia passo a passo para ativar a verificação de e-mail

---

## Por que configurar o e-mail?

O sistema exige que cada usuário **confirme seu e-mail** antes de acessar o GED.
Após o cadastro, um link de verificação é enviado automaticamente. Sem essa
configuração, os e-mails não serão entregues e ninguém conseguirá ativar a conta.

---

## Opção 1 — Desenvolvimento local: Log (mais simples, sem envio real)

Perfeito para testar o sistema localmente sem precisar de e-mail real.
O "e-mail" vai aparecer no arquivo `storage/logs/laravel.log`.

```env
MAIL_MAILER=log
```

Para ver o link de verificação gerado, após o cadastro execute:
```bash
tail -f storage/logs/laravel.log
```
Procure pela linha com `verification.verify` — ela contém a URL de confirmação.

---

## Opção 2 — Desenvolvimento local: Mailtrap (recomendado para testar o e-mail real)

O Mailtrap captura os e-mails sem enviá-los de verdade. Você vê o e-mail exatamente
como o usuário vai receber, no painel do Mailtrap.

**Passos:**
1. Acesse https://mailtrap.io e crie uma conta gratuita
2. Vá em **Email Testing → Inboxes → Add Inbox**
3. Clique na inbox criada → aba **SMTP Settings** → selecione **Laravel 9+**
4. Copie as credenciais e cole no `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=abc123def456        ← copie do Mailtrap
MAIL_PASSWORD=xyz789uvw012        ← copie do Mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@gedlicitacoes.gov.br"
MAIL_FROM_NAME="GED Licitações"
```

---

## Opção 3 — Gmail (produção simples)

> ⚠️ **Importante:** use uma "Senha de app", não sua senha normal do Google.

**Passos:**
1. Ative a **verificação em 2 etapas** na sua conta Google
2. Acesse: https://myaccount.google.com/apppasswords
3. Crie uma senha de app para "Outro (nome personalizado)" → ex: "GED Licitações"
4. Copie a senha gerada (16 caracteres) e cole no `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seuemail@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop   ← senha de app gerada no passo 3
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seuemail@gmail.com"
MAIL_FROM_NAME="GED Licitações"
```

---

## Opção 4 — Outlook / Office 365 (e-mail institucional Microsoft)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=seuemail@prefeitura.gov.br
MAIL_PASSWORD=sua_senha_outlook
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seuemail@prefeitura.gov.br"
MAIL_FROM_NAME="GED Licitações"
```

---

## Opção 5 — Servidor SMTP próprio (Hostinger, cPanel, Locaweb)

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.seudominio.com.br
MAIL_PORT=587
MAIL_USERNAME=noreply@seudominio.com.br
MAIL_PASSWORD=sua_senha_do_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@seudominio.com.br"
MAIL_FROM_NAME="GED Licitações"
```

> Para SSL direto (porta 465), use:
> ```env
> MAIL_PORT=465
> MAIL_ENCRYPTION=ssl
> ```

---

## Como testar após configurar

```bash
php artisan tinker
```

```php
Mail::raw('Teste de e-mail do GED Licitações', function ($m) {
    $m->to('seuemail@teste.com')->subject('Teste GED');
});
```

Se não houver exceção, o e-mail foi enviado com sucesso.

---

## Configuração de filas (produção com volume alto)

Por padrão, o e-mail é enviado de forma **síncrona** (o usuário aguarda o envio).
Para produção com muitos cadastros simultâneos, ative filas:

```env
QUEUE_CONNECTION=database
```

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work --daemon &
```

---

## Problemas comuns

| Erro | Solução |
|------|---------|
| `Connection refused` | Host ou porta incorretos |
| `Authentication failed` | Usuário/senha errados. No Gmail, use senha de app |
| `SSL/TLS handshake failed` | Troque `tls` por `ssl` ou vice-versa |
| `Connection timed out` | Firewall bloqueando a porta 587 — tente a 465 |
| E-mail não chega | Verifique pasta de spam; no Gmail, confirme menos segurança |
| Link expirado | Padrão é 60 min — ajuste `'expire'` em `config/auth.php` |
