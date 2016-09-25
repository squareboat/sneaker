<?php

namespace SquareBoat\Sneaker;

use Exception;
use Illuminate\Log\Writer;
use Illuminate\Config\Repository;

class Sneaker
{
    /**
     * The config implementation.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * The exception handler implementation.
     *
     * @var \SquareBoat\Sneaker\ExceptionHandler
     */
    private $handler;

    /**
     * The css inline mailer implementation.
     *
     * @var \SquareBoat\Sneaker\CssInlineMailer
     */
    private $mailer;

    /**
     * The log writer implementation.
     *
     * @var \Illuminate\Log\Writer
     */
    private $logger;

    /**
     * Create a new sneaker instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @param  \SquareBoat\Sneaker\ExceptionHandler $handler
     * @param  \SquareBoat\Sneaker\CssInlineMailer $mailer
     * @param  \Illuminate\Log\Writer $logger
     * @return void
     */
    public function __construct(Repository $config,
                                ExceptionHandler $handler,
                                CssInlineMailer $mailer,
                                Writer $logger)
    {
        $this->config = $config;

        $this->handler = $handler;

        $this->mailer = $mailer;

        $this->logger = $logger;
    }

    /**
     * Checks an exception which should be tracked and captures it if applicable.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function captureException(Exception $exception)
    {        
        try {
            if ($this->isSilent()) {
                return;
            }

            if ($this->isExceptionFromBot()) {
                return;
            }

            if ($this->shouldCapture($exception)) {
                $this->capture($exception);
            }
        } catch (Exception $e) {
            $this->logger->error(sprintf(
                'Exception thrown in Sneaker when capturing an exception (%s: %s)',
                get_class($e), $e->getMessage()
            ));

            $this->logger->error($e);
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

        $subject = $this->handler->convertExceptionToString($exception);

        $body = $this->handler->convertExceptionToHtml($exception);

        $this->mailer->send($body, function($message) use($recipients, $subject) {
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

        if (! is_array($capture)) {
            return false;
        }

        if (in_array('*', $capture)) {
            return true;
        }

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
        $ignored_bots = $this->config->get('sneaker.ignored_bots');

        $agent = array_key_exists('HTTP_USER_AGENT', $_SERVER)
                    ? strtolower($_SERVER['HTTP_USER_AGENT'])
                    : null;

        if (is_null($agent)) {
            return false;
        }

        foreach ($ignored_bots as $bot) {
            if ((strpos($agent, $bot) !== false)) {
                return true;
            }
        }

        return false;
    }
}
