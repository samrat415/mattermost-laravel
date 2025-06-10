<?php

namespace Samrat415\MattermostLaravel\Commands;

use Illuminate\Console\Command;

class MattermostLaravelCommand extends Command
{
    public $signature = 'mattermost-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
