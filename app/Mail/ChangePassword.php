<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PasswordReset;

class ChangePassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The token to be sent to user.
     *
     * @var PasswordReset
     */
    public PasswordReset $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PasswordReset $token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return 
     */
    public function build()
    {
        return $this->subject("Reset Password")->view('mail.reset_password');
    }
}
