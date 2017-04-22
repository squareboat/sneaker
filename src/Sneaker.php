<?php

namespace SquareBoat\Sneaker;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Config\Repository;
use SquareBoat\Sneaker\Notifications\ExceptionCaught;
use SquareBoat\Sneaker\Exceptions\Handler as ExceptionHandler;

class Sneaker
{
    /**
     * The config implementation.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;

    /**
     * The request implementation.
     *
     * @var \SquareBoat\Sneaker\Request
     */
    private $request;

    /**
     * The log writer implementation.
     *
     * @var \Illuminate\Contracts\Logging\Log
     */
    private $logger;

    /**
     * The meta data to be added in sneaker notifications.
     *
     * @var array
     */
    private $metaData = [];

    /**
     * Create a new sneaker instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository $config
     * @param  \SquareBoat\Sneaker\Request $request
     * @param  \Illuminate\Contracts\Logging\Log $logger
     * @return void
     */
    public function __construct(Repository $config, Request $request, Log $logger)
    {
        $this->config = $config;

        $this->request = $request;

        $this->logger = $logger;
    }

    /**
     * Checks an exception which should be tracked and captures it if applicable.
     *
     * @param  \Exception $exception
     * @param  bool $sneaking
     * @return void
     */
    public function captureException(Exception $exception, $sneaking = false)
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
            $this->logSneakerException($e);

            if ($sneaking) {
                throw $e;
            }
        }
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

    /**
     * Capture an exception.
     * 
     * @param  \Exception $exception
     * @return void
     */
    private function capture($exception)
    {
        $report = $this->getReport($exception);

        $notifiable = $this->getNotifiable();

        $notifiable->notify(
            new ExceptionCaught($report, $this->config->get('sneaker.notifications'))
        );
    }

    /**
     * Get the report of exception.
     * 
     * @param  \Exception $exception
     * @return \SquareBoat\Sneaker\Report
     */
    private function getReport($exception)
    {
        $handler = new ExceptionHandler($exception);

        return (new Report)
                ->setEnv($this->config->get('app.env'))
                ->setRequest($this->request->getMetaData())
                ->setUser(Arr::get($this->metaData, 'user'))
                ->setExtra(Arr::get($this->metaData, 'extra'))
                ->setName($handler->getExceptionName())
                ->setHtml($handler->convertExceptionToHtml())
                ->setMessage($handler->convertExceptionToMessage())
                ->setStacktrace($handler->convertExceptionToStacktrace());
    }

    /**
     * Get the notifiable.
     * 
     * @return \SquareBoat\Sneaker\Notifiable
     */
    private function getNotifiable()
    {
        return new Notifiable([
            'emails' => $this->config->get('sneaker.mail.to'),
            'slack_webhook_url' => $this->config->get('sneaker.slack.webhook_url')
        ]);
    }

    /**
     * Logs the exception thrown by Sneaker itself.
     * 
     * @param \Exception $exception
     */
    private function logSneakerException(Exception $exception)
    {
        $this->logger->error(sprintf(
            'Exception thrown in Sneaker when capturing an exception (%s: %s)',
            get_class($exception), $exception->getMessage()
        ));

        $this->logger->error($exception);
    }

    /**
     * Execute the user context callback.
     * 
     * @param  callable  $callback
     * @return void
     */
    public function userContext($callback)
    {
        $this->metaData['user'] = call_user_func($callback);
    }

    /**
     * Execute the extra context callback.
     * 
     * @param  callable  $callback
     * @return void
     */
    public function extraContext($callback)
    {
        $this->metaData['extra'] = call_user_func($callback);
    }
}
