<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OoharaScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:oohara {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'URLからその講義の動画一覧をダウンロードします';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = $this->argument('url');
        echo $url;
    }
}
