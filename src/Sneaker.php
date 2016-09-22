<?php

namespace Squareboat\Sneaker;

use Exception;
use Illuminate\View\Factory;
use Illuminate\Config\Repository;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class Sneaker
{
    /**
     * The config implementation.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * The view factory implementation.
     * 
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * The css inline mailer implementation.
     *
     * @var \Squareboat\Sneaker\CssInlineMailer
     */
    private $mailer;

    /**
     * Create a new sneaker instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @param  \Illuminate\View\Factory $view
     * @param  \Squareboat\Sneaker\CssInlineMailer $mailer
     * @return void
     */
    function __construct(Repository $config, Factory $view, CssInlineMailer $mailer)
    {
        $this->config = $config;

        $this->view = $view;

        $this->mailer = $mailer;
    }

    /**
     * Checks an exception which should be tracked and captures it if applicable.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function captureException(Exception $exception)
    {
        if($this->isSilent()) {
            return;
        }

        if($this->isExceptionFromBot()) {
            return;
        }

        if($this->shouldCapture($exception)) {
            $this->capture($exception);
        }
    }

    /**
     * Capture an exception.
     * 
     * @param  \Exception $exception
     * @return void
     */
    private function capture($exception)
    {
        $recipients = $this->config->get('sneaker.to');

        $subject = $this->view->make('sneaker::email.subject', compact('exception'));

        $body = $this->convertExceptionToHtml($exception);

        $this->mailer->send($body, function($message) use($recipients, $subject){
            $message->to($recipients)->subject($subject);
        });
    }

    /**
     * Checks if sneaker is silent.
     * 
     * @return boolean
     */
    private function isSilent()
    {
        return $this->config->get('sneaker.silent');
    }

    /**
     * Determine if the exception is in the "capture" list.
     * 
     * @param  Exception $exception
     * @return boolean
     */
    private function shouldCapture(Exception $exception)
    {
        $capture = $this->config->get('sneaker.capture');

        foreach ($capture as $type) {
            if ($exception instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the exception is from the bot.
     * 
     * @return boolean
     */
    private function isExceptionFromBot()
    {
        $ignored_bots = $this->config->get("sneaker.ignored_bots", []);

        $agent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? strtolower($_SERVER['HTTP_USER_AGENT']) : null;

        foreach ($ignored_bots as $bot) {
            if (($agent && strpos($agent, $bot) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a html for the given exception.
     *
     * @param  \Exception  $exception
     * @return string
     */
    private function convertExceptionToHtml($exception)
    {
        $flattened = FlattenException::create($exception);

        $handler = new SymfonyExceptionHandler();

        return $this->decorate($handler->getContent($flattened), $handler->getStylesheet($flattened), $exception);
    }

    /**
     * Get the html response content.
     *
     * @param  string  $content
     * @param  string  $css
     * @return string
     */
    private function decorate($content, $css, $exception)
    {
        return $this->view->make('sneaker::email.body', compact('content', 'css', 'exception'))->render();
    }
}
