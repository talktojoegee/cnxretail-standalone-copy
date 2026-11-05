<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $token, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, User $user)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@cnxretail.com')
            ->subject(config('app.name').' Password Reset')
            ->markdown('mails.user.reset-password');
    }
}
