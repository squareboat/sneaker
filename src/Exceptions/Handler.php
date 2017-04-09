<?php

namespace SquareBoat\Sneaker\Exceptions;

use Exception;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class Handler
{
    /**
     * The exception instance.
     * 
     * @var \Exception
     */
    private $exception;

    /**
     * Create a new exception handler instance.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Get the name of given exception.
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return get_class($this->exception);
    }

    /**
     * Convert the given exception to a readable message.
     * 
     * @return string
     */
    public function convertExceptionToMessage()
    {
        return sprintf("Exception '%s' with message '%s' in %s",
            $this->getExceptionName(),
            $this->exception->getMessage(),
            $this->exception->getFile()
        );
    }

    /**
     * Convert the given exception to a stack trace string.
     * 
     * @return string
     */
    public function convertExceptionToStacktrace()
    {
        return $this->exception->getTraceAsString();
    }

    /**
     * Convert the given exception to a html.
     *
     * @return string
     */
    public function convertExceptionToHtml()
    {
        $flattened = $this->getFlattenedException($this->exception);

        $handler = new SymfonyExceptionHandler();

        return [
            'content' => $this->removeTitle($handler->getContent($flattened)),
            'stylesheet' => $handler->getStylesheet($flattened)
        ];
    }

    /**
     * Converts the Exception in a PHP Exception to be able to serialize it.
     * 
     * @param  \Exception $exception
     * @return \Symfony\Component\Debug\Exception\FlattenException
     */
    private function getFlattenedException($exception)
    {
        if (! $exception instanceof FlattenException) {
            $exception = FlattenException::create($exception);
        }

        return $exception;
    }

    /**
     * Removes title from content as it is same for all exceptions and has no real value.
     * 
     * @param  string $content
     * @return string
     */
    private function removeTitle($content)
    {
        $titles = [
            'Whoops, looks like something went wrong.',
            'Sorry, the page you are looking for could not be found.',
        ];

        foreach ($titles as $title) {
            $content = str_replace("<h1>{$title}</h1>", '', $content);
        }

        return $content;
    }
}
