<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConviteUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $tempPassword,
        public ?string $nome = null,
        public ?Usuario $usuario = null,
        public ?string $remetenteEmail = null,
        public ?string $inviteUrl = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite de Acesso - Recanto Betel',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.convite-usuario',
        );
    }
}
