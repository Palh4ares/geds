<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends BaseVerifyEmail
{
    /**
     * Gera a URL assinada de verificação (válida por 60 minutos).
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * E-mail enviado ao usuário.
     */
    public function toMail($notifiable): MailMessage
    {
        $url     = $this->verificationUrl($notifiable);
        $nome    = $notifiable->name;
        $sistema = config('app.name', 'GED Licitações');

        return (new MailMessage)
            ->subject("Confirme seu e-mail — {$sistema}")
            ->view('emails.verify-email', compact('url', 'nome', 'sistema'));
    }
}
