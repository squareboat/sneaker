<?php

namespace SquareBoat\Sneaker\Notifications;

use Illuminate\Bus\Queueable;
use SquareBoat\Sneaker\Report;
use SquareBoat\Sneaker\Markdown;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class ExceptionCaught extends Notification
{
    use Queueable;

    /**
     * The report implementation.
     * 
     * @var \SquareBoat\Sneaker\Report
     */
    private $report;

    /**
     * The channels on which the notification will be delivered.
     * 
     * @var array
     */
    private $channels;

    /**
     * Create a new notification instance.
     *
     * @param \SquareBoat\Sneaker\Report $report
     * @param  array  $channels
     * @return void
     */
    public function __construct(Report $report, array $channels)
    {
        $this->report = $report;

        $this->channels = $channels;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->getSubject())
                    ->view('sneaker::email.body', ['report' => $this->report]);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                    ->error()
                    ->content($this->getSubject())
                    ->attachment(function ($attachment) {
                        $attachment->title($this->report->getMessage())
                                   ->content("```\n" . $this->report->getStacktrace() . "\n```")
                                   ->markdown(['title', 'text'])
                                   ->timestamp($this->report->getTime())
                                   ->footer('Sneaker');
                    });
    }

    /**
     * Get the subject of notification.
     * 
     * @return string
     */
    private function getSubject()
    {
        return sprintf("[Sneaker] | %s | Server - %s | Environment - %s",
            $this->report->getName(),
            request()->server('SERVER_NAME'),
            $this->report->getEnv()
        );        
    }
}
