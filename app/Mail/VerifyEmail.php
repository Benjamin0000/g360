<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailVerify as Token;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The token to be sent to user.
     *
     * @var Token
     */
    public Token $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Email verification")->view('mail.email_verify');
    }

}
