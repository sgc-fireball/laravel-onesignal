<?php

namespace HRDNS\LaravelPackages\OneSignal\Console\Commands;

use Illuminate\Console\Command;

class OneSignalSendCommand extends Command
{

    /** @var string */
    protected $signature = 'hrdns:onesignal:send';

    /** @var string */
    protected $description = 'Sending test push message.';

    /**
     * @return int
     */
    public function handle(): int
    {
        onesignal()->createNotification([
            'included_segments' => ['All'],
            'contents' => [
                'en' => 'Content'
            ],
            'headings'  => [
                'en' => 'Heading'
            ],
            'url' => 'http://www.google.com',
            'data' => ['data_to_app'=>'test1234'],
        ]);
        return 0;
    }

}
