<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RedefinirSenhaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $novaSenha,
        public ?string $nome = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Redefinição de Senha - Recanto Betel',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.redefinir-senha',
        );
    }
}
