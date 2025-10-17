<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public $fullName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, string $firstName, string $lastName)
    {
        $this->otp = $otp;
        $this->fullName = trim($firstName.' '.$lastName);
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Your Mobiplay Verification Code')
            ->view('emails.otp')
            ->with([
                'otp' => $this->otp,
                'fullName' => $this->fullName,
            ]);
    }
}
