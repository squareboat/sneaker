<?php

namespace SquareBoat\Sneaker;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionMailer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;

    /**
     * The body of the message.
     *
     * @var string
     */
    public $body;

    /**
     * Sender's address.
     *
     * @var string
     */
    public $customFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body, $from)
    {
        $this->subject = $subject;

        $this->body = $body;

        $this->customFrom = $from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->customFrom) {
            $this->from($this->customFrom);
        }

        return $this->view('sneaker::raw')
                    ->with('content', $this->body);
    }
}
