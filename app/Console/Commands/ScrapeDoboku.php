<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Goutte\Client;

class ScrapeDoboku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScrapeDoboku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '土木会社のスクレイピング';

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
        $baseUrl = "https://kensetsu-search.com/db_companies/search?db_companies_search%5Bcapital_max%5D=&db_companies_search%5Bcapital_min%5D=&db_companies_search%5Bfounded_max%5D=2020&db_companies_search%5Bfounded_min%5D=2018&db_companies_search%5Bfree_word%5D=&db_companies_search%5Boffice_type%5D=0&db_companies_search%5Bprefecture_names%5D%5B%5D=%E6%9D%B1%E4%BA%AC%E9%83%BD&page=";

        $client = new Client();

        collect(range(1, 334))->each(function ($page) use ($client, $baseUrl) {
            $crawler = $client->request('GET', "{$baseUrl}{$page}");

            $selector = ".l-main.-right > div.c-panel";

            $crawler->filter($selector)->each(function ($node) {
                try {
                    $nameSelector = ".p-db-company-search__item__header > a";
                    $addressSelector = ".p-db-company-search__item__address__content";
                    $moneySelector = ".p-db-company-search__item__capital__content";
                    $yearSelector = ".p-db-company-search__item__founded__content";
                    $memberSelector = ".p-db-company-search__item__members__content";

                    $record = [
                        'name' => $node->filter($nameSelector)->first()->text(),
                        'address' => $node->filter($addressSelector)->first()->text(),
                        'money' => (int)$node->filter($moneySelector)->first()->text() ?? null,
                        'year' => (int)$node->filter($yearSelector)->first()->text() ?? null,
                        'member' => (int)$node->filter($memberSelector)->first()->text() ?? null
                    ];

                    Company::create($record);
                } catch (\Throwable $th) {
                    $this->line($th->getMessage());
                }
            });

            $startPage = $page * 30 - 29;
            $endPage = $page * 30;
            $this->line("{$startPage} ~ {$endPage} 件を取得しました");
            sleep(1);
        });
    }
}
