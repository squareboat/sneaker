<?php

namespace SquareBoat\Sneaker;

use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Mail\Mailer;
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
     * The mailer instance.
     * 
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * The log writer implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Create a new sneaker instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @param  \SquareBoat\Sneaker\ExceptionHandler $handler
     * @param  \Illuminate\Contracts\Mail\Mailer $mailer
     * @param  \Psr\Log\LoggerInterface $logger
     * @return void
     */
    public function __construct(Repository $config,
                                ExceptionHandler $handler,
                                Mailer $mailer,
                                LoggerInterface $logger)
    {
        $this->config = $config;

        $this->handler = $handler;

        $this->mailer = $mailer;

        $this->logger = $logger;
    }

    /**
     * Checks an exception which should be tracked and captures it if applicable.
     *
     * @param  \Throwable|\Exception $exception
     * @return void
     */
    public function captureException($exception, $sneaking = false)
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

            if ($sneaking) {
                throw $e;
            }
        }
    }

    /**
     * Capture an exception.
     * 
     * @param  \Exception|\Throwable $exception
     * @return void
     */
    private function capture($exception)
    {
        $recipients = $this->config->get('sneaker.to');

        $subject = $this->handler->convertExceptionToString($exception);

        $body = $this->handler->convertExceptionToHtml($exception);

        $this->mailer->to($recipients)->send(new ExceptionMailer($subject, $body));
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
     * @param  \Throwable|\Exception $exception
     * @return boolean
     */
    private function shouldCapture($exception)
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
