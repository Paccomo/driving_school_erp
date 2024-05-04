<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class contractRequestDenied extends Mailable
{
    use Queueable, SerializesModels;

    private $phoneNum;
    private $email;
    private $reqType;
    private $date;
    private $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(array $values)
    {
        $this->phoneNum = $values['phoneNum'];
        $this->email = $values['email'];
        $this->reqType = $values['reqType'];
        $this->date = $values['date'];
        $this->reason = $values['reason'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sutarties užklausa atmesta',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.contractReject',
            with: [
                'phoneNum' => $this->phoneNum,
                'email' => $this->email,
                'reqType' => $this->reqType,
                'date' => $this->date,
                'reason' => $this->reason,
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
