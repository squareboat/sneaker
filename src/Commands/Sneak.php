<?php

namespace SquareBoat\Sneaker\Commands;

use Exception;
use SquareBoat\Sneaker\Sneaker;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use SquareBoat\Sneaker\Exceptions\DummyException;
use Symfony\Component\Console\Application as ConsoleApplication;

class Sneak extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sneaker:sneak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if sneaker is working.';

    /**
     * The config implementation.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * The sneaker implementation.
     *
     * @var \SquareBoat\Sneaker\Sneaker
     */
    private $sneaker;

    /**
     * Create a sneak command instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @param  \SquareBoat\Sneaker\Sneaker $sneaker
     * @return void
     */
    public function __construct(Repository $config, Sneaker $sneaker)
    {
        parent::__construct();

        $this->config = $config;

        $this->sneaker = $sneaker;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->overrideConfig();

        try {
            $this->sneaker->userContext(function() {
                return [
                    'id' => 10,
                    'name' => 'John Doe'
                ];
            })
            ->extraContext(function() {
                return [
                    'App' => 'Project X',
                    'Version' => 'v3.0.0'
                ];
            })
            ->captureException(new DummyException, true);

            $this->info('Sneaker is working fine ✅');
        } catch (Exception $exception) {
            (new ConsoleApplication)->renderException($exception, $this->output);
        }
    }

    /**
     * Overriding the default configurations.
     * 
     * @return void
     */
    public function overrideConfig()
    {
        $this->config->set('queue.default', 'sync');

        $this->config->set('sneaker.capture', [DummyException::class]);
    }
}
