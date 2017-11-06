<?php

namespace RenePardon\GelfSupport\Commands;

use Exception;
use Illuminate\Console\Command;

/**
 * Class TestLogging
 *
 * @package RenePardon\GelfSupport\Commands
 */
class TestLogging extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rp:gelf-support:test-logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Throw an exception to test the logging integration';

    /**
     * @throws Exception
     */
    public function fire()
    {
        throw new Exception('Test logging exception');
    }
}
