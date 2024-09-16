<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Models\MUserTab;
use Illuminate\Mail\Mailables\Content;

class AuthRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $users,
        public $token
    ) {
        //
    }

    // /**
    //  * Build the message.
    //  */
    public function build(): self
    {
        return $this->view('user::emailregister', [
            'users' => $this->users,
            'token' => $this->token
        ]);
    }
}
