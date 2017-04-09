<?php

namespace SquareBoat\Sneaker;

use Carbon\Carbon;

class Report
{
    /**
     * The error name.
     *
     * @var string
     */
    protected $name;

    /**
     * The error message.
     *
     * @var string
     */
    protected $message;

    /**
     * The error time.
     *
     * @var string
     */
    protected $time;

    /**
     * The application environment.
     *
     * @var string
     */
    protected $env;

    /**
     * The associated stacktrace.
     *
     * @var string
     */
    protected $stacktrace;

    /**
     * The associated html.
     *
     * @var string
     */
    protected $html;

    /**
     * Create a new report instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->time = Carbon::now();
    }

    /**
     * Set the error name.
     *
     * @param  string  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the error name.
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the error message.
     *
     * @param  string  $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the error message.
     *
     * @return  string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the error time.
     *
     * @return  string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the application environment.
     *
     * @param  string  $env
     * @return $this
     */
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get the application environment.
     *
     * @return  string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set the error stacktrace.
     *
     * @param  string  $stacktrace
     * @return $this
     */
    public function setStacktrace($stacktrace)
    {
        $this->stacktrace = $stacktrace;

        return $this;
    }

    /**
     * Get the error stacktrace.
     *
     * @return  string
     */
    public function getStacktrace()
    {
        return $this->stacktrace;
    }

    /**
     * Set the error html.
     *
     * @param  string  $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get the error html.
     *
     * @return  string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Get the error html content.
     *
     * @return  string
     */
    public function getHtmlContent()
    {
        return $this->html['content'];
    }

    /**
     * Get the error html content.
     *
     * @return  string
     */
    public function getHtmlStylesheet()
    {
        return $this->html['stylesheet'];
    }
}
