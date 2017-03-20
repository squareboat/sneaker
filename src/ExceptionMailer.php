<?php

namespace SquareBoat\Sneaker;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body)
    {
        $this->subject = $subject;

        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('sneaker::raw')
                    ->with('content', $this->convertCssToInlineStyles($this->body));
    }

    /**
     * Inlines the css into the given html.
     * 
     * @param string $content
     * @return string
     */
    private function convertCssToInlineStyles($content)
    {
        $converter = new CssToInlineStyles();

        return $converter->convert($content);
    }
}
