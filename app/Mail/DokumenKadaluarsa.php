<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DokumenKadaluarsa extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $documents;
    public int $count;

    public function __construct(Collection $documents)
    {
        $this->documents = $documents;
        $this->count = $documents->count();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[{$this->count}] Dokumen Kadaluarsa — " . now()->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.dokumen-kadaluarsa',
        );
    }
}
