<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class registerInfo extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $pw;
    private $url;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $pw, $URL)
    {
        $this->pw = $pw;
        $this->email = $email;
        $this->url = $URL;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nauja paskyra',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.user',
            with: [
                "email" => $this->email,
                "pw" => $this->pw,
                'url' => $this->url,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
