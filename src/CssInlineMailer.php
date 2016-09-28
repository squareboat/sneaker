<?php

namespace SquareBoat\Sneaker;

use Illuminate\Mail\Mailer;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class CssInlineMailer
{
    /**
     * The Mailer instance.
     * 
     * @var \Illuminate\Mail\Mailer
     */
    private $mailer;

    /**
     * The css to inline styles instance.
     * 
     * @var \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles
     */
    private $converter;

    /**
     * Create a new css inline mailer instance.
     * 
     * @param \Illuminate\Mail\Mailer $mailer
     * @param \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles $converter
     * @return void
     */
    public function __construct(Mailer $mailer, CssToInlineStyles $converter)
    {
        $this->mailer = $mailer;

        $this->converter = $converter;
    }

    /**
     * Sends a new message.
     *
     * @param string $content
     * @param \Closure|string  $callback
     * @return void
     */
    public function send($content, $callback)
    {
        $content = $this->convertCssToInlineStyles($content);

        $this->mailer->queue('sneaker::raw', compact('content'), $callback);
    }

    /**
     * Inlines the css into the given html.
     * 
     * @param string $content
     * @return string
     */
    private function convertCssToInlineStyles($content)
    {
        return $this->converter->convert($content);
    }
}
