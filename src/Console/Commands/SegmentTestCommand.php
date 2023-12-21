<?php

namespace Hatchet\Segment\Console\Commands;

use Hatchet\Segment\Facades\Segment;
use Illuminate\Console\Command;

class SegmentTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'segment:test {type=page} {--driver=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit a test segment event';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');
        $driver = $this->option('driver') ?: null;

        $success = Segment::driver($driver)->{$type}([]);

        if ($success) {
            $this->info('Segment event lodged');
        } else {
            $this->error('Segment event failed');
        }

        return $success ? self::SUCCESS : self::FAILURE;
    }
}
