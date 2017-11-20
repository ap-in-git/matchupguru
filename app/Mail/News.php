<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class News extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $message,$sub,$link;

    public function __construct($sub,$message,$link)
    {
        $this->sub=$sub;
        $this->message=$message;
        $this->link=$link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->sub)->markdown('email.news',[
          "message"=>$this->message,
          "link"=>$this->link
        ]);
    }
}
