<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class contractRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    private $reqType;
    private $init;
    private $date;
    private $addr;

    /**
     * Create a new message instance.
     */
    public function __construct(array $values)
    {
        $this->reqType = $values['reqType'];
        $this->init = $values['init'];
        $this->date = $values['date'];
        $this->addr = $values['addr'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sutarties užklausa priimta',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.contractApprove',
            with: [
                "init" => $this->init,
                "reqType" => $this->reqType,
                'date' => $this->date,
                'addr' => $this->addr
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
