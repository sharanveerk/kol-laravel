<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // echo '<pre>'; print_r($this->details['url']); die;
        return $this->subject('hello this is an email verification trial......')
                    ->view('email.test')
                    ->with([
                        'url'=> $this->details['url'], //this works without queue
                        
                    ]);
    }
    public function build1()
    {
        // echo '<pre>'; print_r($this->details['url']); die;
        return $this->subject('hello this is a link to password reset......')
                    ->view('email.test')
                    ->with([
                        'url'=>$this->details['url'], //this works without queue
                        
                    ]);
    }
}
